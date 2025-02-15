<?php

namespace App\Repository;

use PDO;
use App\Entity\Perfil;
use App\Entity\Projeto;
use Doctrine\ORM\Query;
use App\Entity\Programa;
use App\Entity\Publicacao;
use App\Entity\SituacaoFolha;
use App\Entity\FolhaPagamento;
use App\Entity\AgenciaBancaria;
use App\Repository\RepositoryAbstract;
use Doctrine\Persistence\ManagerRegistry;
use App\Exception\ValorBolsaHasInFolhaException;
use Symfony\Component\HttpFoundation\ParameterBag;

class FolhaPagamentoRepository extends RepositoryAbstract
{
    use \App\Traits\MaskTrait;
    
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AgenciaBancaria::class);
    }
    
    /**
     * @param ParameterBag $params
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function search(ParameterBag $params)
    {
        $qb = $this->createQueryBuilder('fp');
        $qb->join('fp.situacao', 's')
            ->join('fp.publicacao', 'pu')
            ->join('pu.programa', 'pr')
            ->where('fp.stRegistroAtivo = :stAtivo')
            ->setParameter('stAtivo', 'S');

        if ($params->get('publicacao')) {
            $qb->andWhere('fp.publicacao = :publicacao')
                ->setParameter('publicacao', $params->get('publicacao'));
        }
        if ($params->get('situacao')) {
            $qb->andWhere('s.coSeqSituacaoFolha IN(:situacao)')
                ->setParameter('situacao', (array) $params->get('situacao'));
        }
        if ($params->get('tpFolha')) {
            $qb->andWhere('fp.tpFolhaPagamento = :tpFolha')
                ->setParameter('tpFolha', $params->get('tpFolha'));
        }
        if ($params->get('nuAno')) {
            $qb->andWhere('fp.nuAno = :nuAno')
                ->setParameter('nuAno', $params->get('nuAno'));
        }
        if ($params->get('nuMes')) {
            $qb->andWhere('fp.nuMes = :nuMes')
                ->setParameter('nuMes', $params->get('nuMes'));
        }

        if ($params->get('order') && $params->get('direction')) {            
            $qb->orderBy($params->get('order'), $params->get('direction'));
        } elseif (is_array($params->get('order'))) {
            foreach ($params->get('order') as $order => $direction) {
                $qb->addOrderBy($order, $direction);
            }
        } else {
            $qb->addOrderBy('fp.nuAno', 'DESC')
                ->addOrderBy('fp.nuMes', 'DESC')
                ->addOrderBy('fp.tpFolhaPagamento');
        }

        return $qb;
    }

    public function findProjetos($coFolhaPagamento)
    {
        $qb = $this->createQueryBuilder('fp');
        $qb->select('fp, prfp, pu, pr')
                ->join('fp.projetosFolhaPagamento', 'prfp')
                ->join('prfp.projeto', 'pr')
                ->join('pr.publicacao', 'pu')
                ->where('fp.coSeqFolhaPagamento = :coFolhaPagamento')
                ->setParameter('coFolhaPagamento', $coFolhaPagamento);
        
        return $qb->getQuery()->getArrayResult();
    }

    public function searchRelatorioPagamentoNaoAutorizado(ParameterBag $params)
    {
        $queryParams = $queryTypes = [];
        $queryParamsStr = '';

        if ($params->get('nuCpf')) {
            $queryParamsStr .= ' AND G.NU_CPF_CNPJ_PESSOA LIKE ? ';
            $queryParams[] = '%' . trim($params->getAlnum('nuCpf')) . '%';
            $queryTypes[] = \PDO::PARAM_STR;
        }
        if ($params->get('nuMes')) {
            $queryParamsStr .= ' AND FP.NU_MES = ? ';
            $queryParams[] = str_pad($params->get('nuMes'), 2, '0', STR_PAD_LEFT);
            $queryTypes[] = \PDO::PARAM_STR;
        }
        if ($params->get('nuAno')) {
            $queryParamsStr .= ' AND FP.NU_ANO = ? ';
            $queryParams[] = abs($params->get('nuAno'));
            $queryTypes[] = \PDO::PARAM_INT;
        }
        if ($params->get('noPessoa')) {
            $queryParamsStr .= ' AND G.NO_PESSOA LIKE upper(?) ';
            $queryParams[] = '%' . trim(strtoupper($params->get('noPessoa'))) . '%';
            $queryTypes[] = \PDO::PARAM_STR;
        }
        if ($params->get('perfil')) {
            $queryParamsStr .= ' AND F.DS_PERFIL = ? ';
            $queryParams[] = $params->get('perfil');
            $queryTypes[] = \PDO::PARAM_INT;
        }
        if ($params->get('ufCampus')) {
            $queryParamsStr .= ' AND MI.CO_UF_IBGE = ? ';
            $queryParams[] = $params->get('ufCampus');
            $queryTypes[] = \PDO::PARAM_STR;
        }
        if ($params->get('municipioCampus')) {
            $queryParamsStr .= ' AND MI.CO_MUNICIPIO_IBGE = ? ';
            $queryParams[] = $params->get('municipioCampus');
            $queryTypes[] = \PDO::PARAM_STR;
        }
        if($params->get('campus')) {
            $queryParamsStr .= ' AND CI.CO_SEQ_CAMPUS_INSTITUICAO = ? ';
            $queryParams[] = $params->get('campus');
            $queryTypes[]  = \PDO::PARAM_INT;
        }

        if ($params->get('ufSecretaria')) {
            $queryParamsStr .= ' AND MSS.CO_UF_IBGE = ? ';
            $queryParams[] = $params->get('ufSecretaria');
            $queryTypes[] = \PDO::PARAM_STR;
        }
        if ($params->get('municipioSecretaria')) {
            $queryParamsStr .= ' AND MSS.CO_MUNICIPIO_IBGE = ? ';
            $queryParams[] = $params->get('municipioSecretaria');
            $queryTypes[] = \PDO::PARAM_STR;
        }
        if ($params->get('secretaria')) {
            $queryParamsStr .= ' AND SS.NU_CNPJ = ? ';
            $queryParams[] = $params->get('secretaria');
            $queryTypes[] = \PDO::PARAM_STR;
        }
        if ($params->get('instituicao')) {
            $queryParamsStr .= ' AND I.CO_SEQ_INSTITUICAO = ? ';
            $queryParams[] = $params->get('instituicao');
            $queryTypes[] = \PDO::PARAM_INT;
        }

        $query = <<<'SQL'
                        SELECT DISTINCT C.CO_SEQ_PROJETO,
                            C.NU_SIPAR,
                            TRUNC(C.DT_INCLUSAO) DT_INCLUSAO_PROJETO,
                            FP.NU_MES,
                            FP.NU_ANO,
                            D.CO_SEQ_PROJETO_PESSOA,
                            G.NU_CPF_CNPJ_PESSOA,
                            G.NO_PESSOA,
                            F.DS_PERFIL,
                            SUB_INST.NO_INSTITUICAO_PROJETO,
                            SUB_SEC.SECRETARIA_SAUDE,
                            TH.ST_REGISTROATIVO_PROJPES,
                            TH.ST_VOLUNTARIO
            FROM   DBPET.TB_PROJETO C
                   INNER JOIN DBPET.TB_PROJETO_FOLHAPAGAMENTO PFP
                           ON C.CO_SEQ_PROJETO = PFP.CO_PROJETO
                              AND PFP.ST_REGISTRO_ATIVO = 'S'
                              AND C.ST_REGISTRO_ATIVO = 'S'
                   INNER JOIN DBPET.TB_FOLHA_PAGAMENTO FP
                           ON PFP.CO_FOLHA_PAGAMENTO = FP.CO_SEQ_FOLHA_PAGAMENTO
                              AND FP.ST_REGISTRO_ATIVO = 'S'
                              AND FP.TP_FOLHA_PAGAMENTO = 'M'
                              AND FP.CO_SITUACAO_FOLHA <> 7
                   INNER JOIN DBPET.TB_PROJETO_PESSOA D
                           ON C.CO_SEQ_PROJETO = D.CO_PROJETO
                   INNER JOIN DBPET.TB_PESSOA_PERFIL E
                           ON D.CO_PESSOA_PERFIL = E.CO_SEQ_PESSOA_PERFIL
                   INNER JOIN DBPET.TB_PERFIL F
                           ON E.CO_PERFIL = F.CO_SEQ_PERFIL
                   INNER JOIN DBPESSOA.TB_PESSOA G
                           ON E.NU_CPF = G.NU_CPF_CNPJ_PESSOA
                   INNER JOIN DBPET.TH_PARTICIPANTE_FOLHAPAGAMENTO TH
                           ON TH.CO_PROJETO_PESSOA = D.CO_SEQ_PROJETO_PESSOA
                              AND FP.CO_SEQ_FOLHA_PAGAMENTO = TH.CO_FOLHA_PAGAMENTO
                              AND TH.ST_VOLUNTARIO = 'N'
                              AND TH.ST_REGISTROATIVO_PROJPES = 'S'
                   --DADOS CAMPUS INSTITUIÇÃO
                   INNER JOIN DBPET.RL_PROJETO_CAMPUSINTITUICAO PC
                           ON PC.CO_PROJETO = C.CO_SEQ_PROJETO
                              AND PC.ST_REGISTRO_ATIVO = 'S'
                   INNER JOIN DBPET.TB_CAMPUS_INSTITUICAO CI
                           ON PC.CO_CAMPUS_INSTITUICAO = CI.CO_SEQ_CAMPUS_INSTITUICAO
                              AND CI.ST_REGISTRO_ATIVO = 'S'
                   INNER JOIN DBPET.TB_INSTITUICAO I
                           ON CI.CO_INSTITUICAO = I.CO_SEQ_INSTITUICAO
                              AND I.ST_REGISTRO_ATIVO = 'S'
                   --MUNICIPIO INSTITUICAO
                   INNER JOIN DBPESSOA.TB_PESSOA PEI
                           ON PEI.NU_CPF_CNPJ_PESSOA = I.NU_CNPJ
                              AND I.ST_REGISTRO_ATIVO = 'S'
                   INNER JOIN DBGERAL.TB_MUNICIPIO MI
                           ON MI.CO_MUNICIPIO_IBGE = PEI.CO_MUNICIPIO_IBGE
                   --DADOS SECRETARIA DE SAUDE
                   INNER JOIN DBPET.TB_SECRETARIA_SAUDE SS
                           ON SS.CO_PROJETO = C.CO_SEQ_PROJETO
                   INNER JOIN DBPESSOA.TB_PESSOA PES
                           ON SS.NU_CNPJ = PES.NU_CPF_CNPJ_PESSOA
                              AND SS.ST_REGISTRO_ATIVO = 'S'
                   INNER JOIN DBGERAL.TB_MUNICIPIO MSS
                           ON MSS.CO_MUNICIPIO_IBGE = PES.CO_MUNICIPIO_IBGE
                   INNER JOIN (SELECT DISTINCT A2.CO_PROJETO,
                                               LISTAGG('('
                                                       || ( SUBSTR(C2.NU_CNPJ, 1, 2)
                                                            || '.'
                                                            || SUBSTR(C2.NU_CNPJ, 3, 3)
                                                            || '.'
                                                            || SUBSTR(C2.NU_CNPJ, 6, 3)
                                                            || '/'
                                                            || SUBSTR(C2.NU_CNPJ, 9, 4)
                                                            || '-'
                                                            || SUBSTR(C2.NU_CNPJ, 13, 2) )
                                                       || ') '
                                                       || C2.NO_INSTITUICAO_PROJETO, ', ')
                                                 WITHIN GROUP(ORDER BY
                                                 C2.CO_SEQ_INSTITUICAO) OVER(
                                                   PARTITION BY A2.CO_PROJETO)
                          NO_INSTITUICAO_PROJETO
                               FROM   DBPET.RL_PROJETO_CAMPUSINTITUICAO A2
                                      INNER JOIN DBPET.TB_CAMPUS_INSTITUICAO B2
                                              ON A2.CO_CAMPUS_INSTITUICAO =
                                                 B2.CO_SEQ_CAMPUS_INSTITUICAO
                                                 AND A2.ST_REGISTRO_ATIVO = 'S'
                                                 AND B2.ST_REGISTRO_ATIVO = 'S'
                                      INNER JOIN DBPET.TB_INSTITUICAO C2
                                              ON B2.CO_INSTITUICAO = C2.CO_SEQ_INSTITUICAO
                                                 AND C2.ST_REGISTRO_ATIVO = 'S'
                               GROUP  BY A2.CO_PROJETO,
                                         C2.NU_CNPJ,
                                         C2.NO_INSTITUICAO_PROJETO,
                                         C2.CO_SEQ_INSTITUICAO) SUB_INST
                           ON C.CO_SEQ_PROJETO = SUB_INST.CO_PROJETO
                   INNER JOIN (SELECT DISTINCT LISTAGG('('
                                                       || ( SUBSTR(B1.NU_CPF_CNPJ_PESSOA, 1,
                                                            2)
                                                            || '.'
                                                            || SUBSTR(B1.NU_CPF_CNPJ_PESSOA,
                                                               3, 3)
                                                            || '.'
                                                            || SUBSTR(B1.NU_CPF_CNPJ_PESSOA,
                                                               6, 3)
                                                            || '/'
                                                            || SUBSTR(B1.NU_CPF_CNPJ_PESSOA,
                                                               9, 4)
                                                            || '-'
                                                            || SUBSTR(B1.NU_CPF_CNPJ_PESSOA,
                                                               13, 2)
                                                          )
                                                       || ') '
                                                       || B1.NO_PESSOA, ', ')
                                                 WITHIN GROUP(ORDER BY
                                                 B1.NU_CPF_CNPJ_PESSOA) OVER(
                                                   PARTITION BY A1.CO_PROJETO)
                                               SECRETARIA_SAUDE,
                                               A1.CO_PROJETO
                               FROM   DBPET.TB_SECRETARIA_SAUDE A1
                                      INNER JOIN DBPESSOA.TB_PESSOA B1
                                              ON A1.NU_CNPJ = B1.NU_CPF_CNPJ_PESSOA
                                                 AND A1.ST_REGISTRO_ATIVO = 'S'
                               GROUP  BY A1.CO_PROJETO,
                                         B1.NU_CPF_CNPJ_PESSOA,
                                         B1.NO_PESSOA) SUB_SEC
                           ON C.CO_SEQ_PROJETO = SUB_SEC.CO_PROJETO
            WHERE  C.NU_SIPAR <> '99999.999999/9999-99'
                   AND D.CO_SEQ_PROJETO_PESSOA NOT IN(SELECT AF.CO_PROJETO_PESSOA
                                                      FROM   DBPET.TB_AUTORIZACAO_FOLHA AF
                                                      WHERE
                           AF.CO_PROJ_FOLHA_PAGAM = PFP.CO_SEQ_PROJ_FOLHA_PAGAM
                           AND AF.ST_REGISTRO_ATIVO = 'S')    
SQL;

        $orderByClausule = ' ORDER BY C.NU_SIPAR, G.NO_PESSOA, FP.NU_MES, FP.NU_ANO ASC ';
        if ($params->get('order-by') && $params->get('sort')) {
            $orderByClausule = ' ORDER BY ' . $params->get('order-by') . ' ' . $params->get('sort');
        }

        $stmt = $this->_em->getConnection()->executeQuery(
                $query . $queryParamsStr . $orderByClausule, $queryParams, $queryTypes
        );

        return $stmt->fetchAll();
    }


    /**
     * @param ParameterBag $params
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function findParticipantesProjetoFolhaPagamento($arFolhaPagamento)
    {

        $repository = $this->getEntityManager()->getRepository('App\Entity\ProjetoFolhaPagamento');
        $qb = $repository->createQueryBuilder('pf');

        //$qb->select('pp.coSeqProjetoPessoa, fp.coSeqFolhaPagamento, pp.stRegistroAtivo, pp.stVoluntarioProjeto')
        $qb->innerJoin('pf.projeto', 'p')
            ->innerJoin('pf.folhaPagamento', 'fp')
            ->innerJoin('p.projetosPessoas', 'pp')
            ->where($qb->expr()->eq('pf.stRegistroAtivo', $qb->expr()->literal('S')))
            ->andWhere($qb->expr()->eq('p.stRegistroAtivo', $qb->expr()->literal('S')))
            ->andWhere($qb->expr()->eq('fp.stRegistroAtivo', $qb->expr()->literal('S')));

        if ($arFolhaPagamento) {
            $qb->andWhere($qb->expr()->in('pf.coSeqProjFolhaPagam', $arFolhaPagamento));
        }

        return $qb->getQuery()->getResult();
    }
    
    /**
     * 
     * @param string $nuMes
     * @param string $nuAno
     * @throws \UnexpectedValueException
     */
    public function checkNuMesNuAnoHasInFolha($nuMes, $nuAno)
    {
        $folhaPagamento = $this->findOneBy([
            'nuMes' => $nuMes,
            'nuAno' => $nuAno,
            'stRegistroAtivo' => 'S',
        ]);
        
        if ($folhaPagamento) {
            throw new ValorBolsaHasInFolhaException();
        }
    }
    
    /**
     * 
     * @param Publicacao $publicacao
     * @return FolhaPagamento
     * @throws \UnexpectedValueException
     */
    public function getFolhaAbertaByPublicacao(Publicacao $publicacao)
    {
        $result = $this->createQueryBuilder('fp')
            ->where('fp.stRegistroAtivo = :stAtivo')
            ->andWhere('fp.situacao = :situacaoFolhaPagamento')
            ->andWhere('fp.publicacao = :publicacao')
            ->andWhere('fp.tpFolhaPagamento = :tpFolhaPagamento')
            ->setMaxResults(1)
            ->setParameters([
                'stAtivo' => 'S',
                'publicacao' => $publicacao->getCoSeqPublicacao(),
                'situacaoFolhaPagamento' => SituacaoFolha::ABERTA,
                'tpFolhaPagamento' => FolhaPagamento::MENSAL,
            ])->getQuery()->getOneOrNullResult();
        
        if (!$result) {
            throw new \UnexpectedValueException('Não existe folha aberta para o programa' . $publicacao->getDescricaoCompleta());
        }
        
        return $result;
    }

    /**
     * 
     * @param Publicacao $publicacao
     * @param string $tpFolhaPagamento
     * @return FolhaPagamento[]
     */
    public function findByPublicacaoAndTipo(
        Publicacao $publicacao,
        $tpFolhaPagamento = FolhaPagamento::MENSAL
    ) {
        return $this->createQueryBuilder('fp')
            ->where('fp.stRegistroAtivo = :stAtivo')            
            ->andWhere('fp.publicacao = :publicacao')
            ->andWhere('fp.tpFolhaPagamento = :tpFolhaPagamento')            
            ->orderBy('fp.nuAno', 'DESC')
            ->addOrderBy('fp.nuMes', 'DESC')
            ->setParameters([
                'stAtivo' => 'S',
                'publicacao' => $publicacao->getCoSeqPublicacao(),                
                'tpFolhaPagamento' => $tpFolhaPagamento,
            ])->getQuery()->getResult();
    }
    
    /**
     * 
     * @param Publicacao $publicacao
     * @return FolhaPagamento[]
     */
    public function findByPublicacao(Publicacao $publicacao)
    {
        return $this->createQueryBuilder('fp')
            ->where('fp.stRegistroAtivo = :stAtivo')            
            ->andWhere('fp.publicacao = :publicacao')                       
            ->orderBy('fp.nuAno', 'DESC')
            ->addOrderBy('fp.nuMes', 'DESC')
            ->setParameters([
                'stAtivo' => 'S',
                'publicacao' => $publicacao->getCoSeqPublicacao(),                
            ])->getQuery()->getResult();
    }

    /**
     * 
     * @param ParameterBag $pb
     * @return array
     */
    public function relatorioDetalhado(ParameterBag $pb)
    {
        $qb = $this->createQueryBuilder('fp');
        
        $select = [
            'partial fp.{coSeqFolhaPagamento, nuAno, nuMes, tpFolhaPagamento}',
            'partial pub.{coSeqPublicacao, nuPublicacao, dsPublicacao}',
            'partial prog.{coSeqPrograma, dsPrograma}',
            'partial sf.{coSeqSituacaoFolha, dsSituacaoFolha}',
            'partial pfp.{coSeqProjFolhaPagam, stDeclaracao, dsJustificativa, dtInclusao}',
            'partial proj.{coSeqProjeto, nuSipar}',
            'partial ppc.{coSeqPessoaPerfil}',
            'partial pfc.{nuCpf}',
            'partial pc.{nuCpfCnpjPessoa, noPessoa}',
            'partial af.{coSeqAutorizacaoFolha, vlBolsa}',
            'partial projp.{coSeqProjetoPessoa}',
            'partial ppga.{coSeqProjpesGrupoatuac}',
            'partial ga.{coSeqGrupoAtuacao, noGrupoAtuacao}',
            'partial pp.{coSeqPessoaPerfil}',
            'partial pf.{nuCpf}',
            'partial p.{nuCpfCnpjPessoa, noPessoa}',
            'partial perf.{coSeqPerfil, dsPerfil}',
        ];
        
        $qb->select(implode(', ', $select))
            /** Programa **/
            ->innerJoin('fp.publicacao', 'pub')
            ->innerJoin('pub.programa', 'prog')
            ->innerJoin('fp.situacao', 'sf')
            /** Projetos **/
            ->innerJoin('fp.projetosFolhaPagamento', 'pfp')
            ->innerJoin('pfp.projeto', 'proj')
            /** Coordenador de Projeto **/
            ->leftJoin('pfp.pessoaPerfil', 'ppc')
            ->leftJoin('ppc.pessoaFisica', 'pfc')
            ->leftJoin('pfc.pessoa', 'pc')
            /** Participantes **/
            ->innerJoin('pfp.autorizacaoFolha', 'af')
            ->innerJoin('af.projetoPessoa', 'projp')
            ->leftJoin('projp.projetoPessoaGrupoAtuacao', 'ppga', 'WITH', 'ppga.stRegistroAtivo = :stAtivo')
            ->leftJoin('ppga.grupoAtuacao', 'ga')
            ->innerJoin('projp.pessoaPerfil', 'pp')
            ->innerJoin('pp.pessoaFisica', 'pf')
            ->innerJoin('pf.pessoa', 'p')
            ->innerJoin('pp.perfil', 'perf')
            ->where('fp.stRegistroAtivo = :stAtivo')
            ->andWhere('pfp.stRegistroAtivo = :stAtivo')
            ->andWhere('af.stRegistroAtivo = :stAtivo')            
            ->setParameter('stAtivo', 'S')            
            ->orderBy('proj.nuSipar', 'ASC')
            ;
        
        if ($pb->get('publicacao')) {
            $qb->andWhere('pub.coSeqPublicacao = :publicacao')
                ->setParameter('publicacao', $pb->get('publicacao'));
        }
        if ($pb->get('nuSei')) {
            $qb->andWhere('proj.nuSipar = :nuSei')
                ->setParameter('nuSei', $pb->get('nuSei'));
        }
        if ($pb->get('coordenador')) {
            $qb->andWhere('ppc.coSeqPessoaPerfil = :coordenador')
                ->setParameter('coordenador', $pb->get('coordenador'));
        }
        if ($pb->get('tpFolha')) {
            $qb->andWhere('fp.tpFolhaPagamento = :tpFolha')
                ->setParameter('tpFolha', $pb->get('tpFolha'));
        }
        if ($pb->get('situacao')) {
            $qb->andWhere('fp.situacao = :situacao')
                ->setParameter('situacao', $pb->get('situacao'));
        }
        if ($pb->get('folhaPagamento')) {
            $qb->andWhere('fp.coSeqFolhaPagamento = :folhaPagamento')
                ->setParameter('folhaPagamento', $pb->get('folhaPagamento'));
        }
        
        return $qb->getQuery()->getScalarResult();
    }
    
    /**
     * 
     * @param FolhaPagamento $folhaPagamento
     * @return FolhaPagamento
     */
    public function getMensalBySuplementar(FolhaPagamento $folhaPagamento)
    {
        return $this->findOneBy([
            'publicacao' => $folhaPagamento->getPublicacao(),
            'nuAno' => $folhaPagamento->getNuAno(),
            'nuMes' => $folhaPagamento->getNuMes(),
            'tpFolhaPagamento' => ($folhaPagamento->isMensal()) ? FolhaPagamento::MENSAL : FolhaPagamento::SUPLEMENTAR,
            'stRegistroAtivo' => 'S',
        ]);
    }

    /**
     * @param ParameterBag $pb
     * @return array
     */
    public function findParticipantesFolhaSuplementar(ParameterBag $pb)
    {
        $qb = $this->getEntityManager()
            ->getRepository('App:ProjetoPessoa')
            ->createQueryBuilder('pp');

        $qb->select([
            'partial pp.{ coSeqProjetoPessoa, stRegistroAtivo }',
            'partial pperf.{ coSeqPessoaPerfil }',
            'partial perfil.{ coSeqPerfil, dsPerfil }',
            'partial pf.{ nuCpf, tpSituacaoCpf }',
            'partial pes.{ nuCpfCnpjPessoa, noPessoa }',
            'partial proj.{ coSeqProjeto, nuSipar }',
            'partial pub.{ coSeqPublicacao }',
            'partial vb.{ coSeqValorBolsaPrograma, vlBolsa }'
            ])->innerJoin('pp.pessoaPerfil', 'pperf')
            ->innerJoin('pperf.perfil', 'perfil')
            ->innerJoin('pperf.pessoaFisica', 'pf')
            ->innerJoin('pf.pessoa', 'pes')
            ->innerJoin('pp.projeto', 'proj')
            ->innerJoin('proj.publicacao', 'pub')
            ->innerJoin('pub.programa', 'prog')
            ->innerJoin('pub.valorBolsaPrograma', 'vb')
            ->where('pp.stVoluntarioProjeto = :stVoluntario')
            ->andWhere('pperf.perfil = vb.perfil')
            ->andWhere('vb.stPeriodoVigencia = :stBolsaVigente')
            ->andWhere('vb.stRegistroAtivo = :stAtivo')
            ->setParameter('stVoluntario', 'N')
            ->setParameter('stAtivo', 'S')
            ->setParameter('stBolsaVigente', 'S')
            ->orderBy('perfil.dsPerfil')
            ->addOrderBy('pes.noPessoa');

        if ($pb->get('publicacao')) {
            $qb->andWhere('proj.publicacao = :publicacao')
                ->setParameter('publicacao', $pb->getInt('publicacao'));
        }
        if ($pb->get('projeto')) {
            $qb->andWhere('pp.projeto = :projeto')
                ->setParameter('projeto', $pb->getInt('projeto'));
        }
        if ($pb->get('nuSipar')) {
            $qb->andWhere('proj.nuSipar = :nuSipar')
                ->setParameter('nuSipar', $pb->get('nuSipar'));
        }
        if ($pb->get('participantes')) {
            $qb->andWhere('pp.coSeqProjetoPessoa NOT IN(:participantes)')
                ->setParameter('participantes', (array) $pb->get('participantes'));
        }
        if ($pb->get('folhaPagamento')) {
            $qb->andWhere(
                $qb->expr()->not(
                    $qb->expr()->exists(
                        'SELECT 1 FROM App:FolhaPagamento fp'
                        .' INNER JOIN fp.projetosFolhaPagamento pfp'
                        .' INNER JOIN pfp.autorizacaoFolha af'
                        .' INNER JOIN af.projetoPessoa ppf'
                        .' WHERE ppf.coSeqProjetoPessoa = pp.coSeqProjetoPessoa'
                        .' AND af.stRegistroAtivo = \'S\''
                        .' AND fp.coSeqFolhaPagamento = :folhaPagamentoSup'
                    )
                )
            )->setParameter('folhaPagamentoSup', $pb->getInt('folhaPagamento'));
        }
        if ($pb->get('cpf')) {
            $qb->andWhere('pf.nuCpf = :cpf')
                ->setParameter('cpf', $pb->getDigits('cpf'));
        }

        return $qb->getQuery()->getArrayResult();
    }
}
