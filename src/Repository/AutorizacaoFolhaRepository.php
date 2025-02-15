<?php

namespace App\Repository;

use App\Entity\AutorizacaoFolha;
use Symfony\Component\HttpFoundation\ParameterBag;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr\Join;
use App\Entity\FolhaPagamento;
use App\Entity\ProjetoFolhaPagamento;
use App\Cpb\DicionarioCpb;

/**
 * AutorizacaoFolhaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AutorizacaoFolhaRepository extends RepositoryAbstract
{
    /**
     * @param ParameterBag $pb
     * @param int $hydrationMode
     * @return array|AutorizacaoFolha[]
     */
    public function search(ParameterBag $pb, $hydrationMode = Query::HYDRATE_OBJECT)
    {
        $qb = $this
            ->createQueryBuilder('af')
            ->select(['af', 'pfp', 'fp', 'proj', 'pp', 'pperf', 'perf', 'pf', 'pes','da','b'])
            ->innerJoin('af.projetoFolhaPagamento', 'pfp')
            ->innerJoin('pfp.folhaPagamento', 'fp')
            ->innerJoin('pfp.projeto', 'proj')
            ->innerJoin('af.projetoPessoa', 'pp')
            ->innerJoin('pp.pessoaPerfil', 'pperf')
            ->innerJoin('pperf.perfil', 'perf')
            ->innerJoin('pperf.pessoaFisica', 'pf')
            ->innerJoin('pf.pessoa', 'pes')
            ->leftJoin('pf.dadoPessoal', 'da')    
            ->leftJoin('da.banco', 'b')    
            ->where('af.stRegistroAtivo = :stAtivo')
            ->andWhere('fp.stRegistroAtivo = :stAtivo')
            ->setParameter('stAtivo', 'S');

        if ($pb->get('publicacao')) {
            $qb->andWhere('fp.publicacao = :publicacao')
                ->setParameter('publicacao', $pb->getInt('publicacao'));
        }
        if ($pb->get('nuSei')) {
            $qb->andWhere('proj.nuSipar LIKE :nuSei')
                ->setParameter('nuSei', $pb->get('nuSei') . '%');
        }
        if ($pb->get('tpFolhaPagamento')) {
            $qb->andWhere('fp.tpFolhaPagamento = :tpFolhaPagamento')
                ->setParameter('tpFolhaPagamento', $pb->get('tpFolhaPagamento'));
        }
        if ($pb->get('ano')) {
            $qb->andWhere('fp.nuAno = :ano')
                ->setParameter('ano', $pb->getInt('ano'));
        }
        if ($pb->get('mes')) {
            $qb->andWhere('fp.nuMes = :mes')
                ->setParameter('mes', str_pad($pb->get('mes'), 2, '0', STR_PAD_LEFT));
        }
        if ($pb->get('folhaPagamento')) {
            $qb->andWhere('fp.coSeqFolhaPagamento = :folhaPagamento')
                ->setParameter('folhaPagamento', $pb->getInt('folhaPagamento'));
        }
        if ($pb->get('situacaoFolha')) {
            $qb->andWhere('fp.situacao IN(:situacao)')
                ->setParameter('situacao', (array) $pb->get('situacaoFolha'));
        }

        $this->orderPagination($qb, $pb);
        return $qb->getQuery()->getResult($hydrationMode);
    }

    /**
     * @param FolhaPagamento $folhaPagamento
     * @param int $situacaoFolha
     */
    public function getByFolhaAndStatus(FolhaPagamento $folhaPagamento, $situacaoFolha = null)
    {
        $rsm = new \Doctrine\ORM\Query\ResultSetMapping;
        
        $sql = <<<SQL
            SELECT 
                E.CO_SEQ_AUTORIZACAO_FOLHA,
                A.NU_MES,
                I.NO_PESSOA,
                G.NU_CPF,
                H.DS_PERFIL,
                SUB_ATUACAO.NO_GRUPO_ATUACAO,
                E.VL_BOLSA,
                C.NU_SIPAR,
                PES_COORD.NO_PESSOA AS NO_COORD
            FROM DBPET.TB_FOLHA_PAGAMENTO A
            INNER JOIN DBPET.TB_PROJETO_FOLHAPAGAMENTO B 
              ON A.CO_SEQ_FOLHA_PAGAMENTO = B.CO_FOLHA_PAGAMENTO
              AND A.ST_REGISTRO_ATIVO = 'S'
              AND B.ST_REGISTRO_ATIVO = 'S'
              AND B.ST_PARECER = 'S'
            INNER JOIN DBPET.TB_PROJETO C 
              ON B.CO_PROJETO = C.CO_SEQ_PROJETO
              AND C.ST_REGISTRO_ATIVO = 'S'
            INNER JOIN DBPET.TB_SITUACAO_PROJETO_FOLHA D 
              ON B.CO_SITUACAO_PROJ_FOLHA = D.CO_SEQ_SITUACAO_PROJ_FOLHA
            INNER JOIN DBPET.TB_AUTORIZACAO_FOLHA E 
              ON B.CO_SEQ_PROJ_FOLHA_PAGAM = E.CO_PROJ_FOLHA_PAGAM
              AND E.ST_REGISTRO_ATIVO = 'S'
              AND E.ST_PARECER = 'S'
            INNER JOIN DBPET.TB_PROJETO_PESSOA F 
              ON E.CO_PROJETO_PESSOA = F.CO_SEQ_PROJETO_PESSOA
            LEFT JOIN (
                      SELECT DISTINCT PP.CO_SEQ_PROJETO_PESSOA, LISTAGG(G.NO_GRUPO_ATUACAO, ', ') WITHIN GROUP(ORDER BY G.CO_SEQ_GRUPO_ATUACAO) OVER(PARTITION BY PP.CO_SEQ_PROJETO_PESSOA) NO_GRUPO_ATUACAO
                          FROM DBPET.TB_PROJETO_PESSOA PP
                      INNER JOIN DBPET.RL_PROJETOPESSOA_GRUPOATUACAO RL
                        ON RL.CO_PROJETO_PESSOA = PP.CO_SEQ_PROJETO_PESSOA
                        AND RL.ST_REGISTRO_ATIVO = 'S'
                        AND PP.ST_REGISTRO_ATIVO = 'S'
                      INNER JOIN DBPET.TB_GRUPO_ATUACAO G
                        ON G.CO_SEQ_GRUPO_ATUACAO = RL.CO_GRUPO_ATUACAO
                        AND G.ST_REGISTRO_ATIVO  = 'S') SUB_ATUACAO
              ON SUB_ATUACAO.CO_SEQ_PROJETO_PESSOA = F.CO_SEQ_PROJETO_PESSOA

            INNER JOIN DBPET.TB_PESSOA_PERFIL G 
              ON F.CO_PESSOA_PERFIL = G.CO_SEQ_PESSOA_PERFIL
            INNER JOIN DBPET.TB_PERFIL H 
              ON G.CO_PERFIL = H.CO_SEQ_PERFIL
            INNER JOIN DBPESSOA.TB_PESSOA I 
              ON G.NU_CPF = I.NU_CPF_CNPJ_PESSOA
            INNER JOIN
                (
                SELECT 
                    J.CO_PROJETO,
                    MIN(J.CO_SEQ_PROJETO_PESSOA) CO_SEQ_PROJETO_PESSOA
                FROM DBPET.TB_PROJETO_PESSOA J
                INNER JOIN DBPET.TB_PESSOA_PERFIL K 
                  ON J.CO_PESSOA_PERFIL = K.CO_SEQ_PESSOA_PERFIL
                INNER JOIN DBPET.TB_PERFIL L 
                  ON K.CO_PERFIL = L.CO_SEQ_PERFIL
                WHERE L.CO_SEQ_PERFIL = 2 
                  AND J.ST_REGISTRO_ATIVO = 'S'
                  AND K.ST_REGISTRO_ATIVO = 'S'
                GROUP BY J.CO_PROJETO
                ) COORD 
              ON C.CO_SEQ_PROJETO = COORD.CO_PROJETO
            INNER JOIN DBPET.TB_PROJETO_PESSOA PP_COORD 
              ON COORD.CO_SEQ_PROJETO_PESSOA = PP_COORD.CO_SEQ_PROJETO_PESSOA
            INNER JOIN DBPET.TB_PESSOA_PERFIL PE_COORD 
              ON PP_COORD.CO_PESSOA_PERFIL = PE_COORD.CO_SEQ_PESSOA_PERFIL
            INNER JOIN DBPESSOA.TB_PESSOA PES_COORD 
              ON PE_COORD.NU_CPF = PES_COORD.NU_CPF_CNPJ_PESSOA
            WHERE A.CO_SEQ_FOLHA_PAGAMENTO = :folhaPagamento
SQL;
        if(!is_null($situacaoFolha)) {
            $sql .= 'AND A.CO_SITUACAO_FOLHA = :situacaoFolha';
        }
        
        $conn = $this->_em->getConnection();
        
        return $conn->fetchAll($sql, array('situacaoFolha' => $situacaoFolha, 'folhaPagamento' => $folhaPagamento->getCoSeqFolhaPagamento()));
    }

    public function getByProjetoFolha(ProjetoFolhaPagamento $folhaPagamento)
    {
        $qb = $this->createQueryBuilder('af');

        $qb->join('af.projetoPessoa', 'pp')
            ->join('pp.pessoaPerfil', 'pperf')
            ->join('pperf.perfil', 'perf')
            ->join('pperf.pessoaFisica', 'pf')
            ->join('pf.pessoa', 'p')
            ->leftJoin('App:ProjetoPessoaGrupoAtuacao', 'ppga', Join::WITH, "pp.coSeqProjetoPessoa = ppga.projetoPessoa and ppga.stRegistroAtivo = 'S' ")
            ->leftJoin('ppga.grupoAtuacao', 'ga')
            ->where($qb->expr()->eq('af.projetoFolhaPagamento', ':projetoFolhaPagamento'))
            ->andWhere($qb->expr()->eq('af.stRegistroAtivo', $qb->expr()->literal('S')))
            ->andWhere($qb->expr()->eq('af.stParecer', $qb->expr()->literal('S')))
            ->setParameter('projetoFolhaPagamento', $folhaPagamento->getCoSeqProjFolhaPagam(), \PDO::PARAM_INT)
            ->orderBy('ga.noGrupoAtuacao')
            ->addOrderBy('perf.dsPerfil')
            ->addOrderBy('p.noPessoa')
        ;

        return $qb->getQuery()->getResult();
    }

    /**
     * 
     * @param ProjetoFolhaPagamento $projetoFolhaPagamento
     * @return array
     */
    public function findQtParticipantesPorGrupoAtuacao(ProjetoFolhaPagamento $projetoFolhaPagamento)
    {
        $qb = $this->createQueryBuilder('af');
        
        return $qb
            ->select('count(pp.coSeqProjetoPessoa) as qtParticipantes, ga.noGrupoAtuacao')
            ->innerJoin('af.projetoPessoa', 'pp')
            ->innerJoin('pp.projetoPessoaGrupoAtuacao', 'ppga')
            ->innerJoin('ppga.grupoAtuacao', 'ga')
            ->where($qb->expr()->eq('af.projetoFolhaPagamento', $projetoFolhaPagamento->getCoSeqProjFolhaPagam()))
            ->andWhere($qb->expr()->eq('af.stRegistroAtivo', $qb->expr()->literal('S'))) 
            ->andWhere($qb->expr()->eq('ppga.stRegistroAtivo', $qb->expr()->literal('S')))
            ->andWhere($qb->expr()->eq('af.stParecer', $qb->expr()->literal('S')))
            ->groupBy('ga.noGrupoAtuacao')
            ->getQuery()
            ->getScalarResult()
        ;
    }    
    
    /**
     * 
     * @param FolhaPagamento $folhaPagamento
     * @param string $partialCpf
     * @param integer $precision
     * @return AutorizacaoFolha
     */
    public function getByFolhaAndPartialCpf(
        FolhaPagamento $folhaPagamento,
        $partialCpf,
        $precision = 9
    ) {
        $qb = $this->createQueryBuilder('af');
        
        return $qb->select('af')
            ->innerJoin('af.projetoFolhaPagamento', 'pfp')
            ->innerJoin('pfp.folhaPagamento', 'fp')
            ->innerJoin('af.projetoPessoa', 'pp')
            ->innerJoin('pp.pessoaPerfil', 'pperf')
            ->innerJoin('pperf.pessoaFisica', 'pf')
            ->where('fp.coSeqFolhaPagamento = :folhaPagamento')
            ->setMaxResults(1)
            ->andWhere(
                $qb->expr()->eq(
                    $qb->expr()->substring('pf.nuCpf', 1, $precision),
                    ':partialCpf'
                )
            )->setParameters([                
                'folhaPagamento' => $folhaPagamento->getCoSeqFolhaPagamento(),
                'partialCpf' => $partialCpf,
            ])->getQuery()->getSingleResult();        
    }
    
    /**
     * 
     * @param FolhaPagamento $folhaPagamento
     * @return AutorizacaoFolha[]
     */
    public function findNaoRetornados(FolhaPagamento $folhaPagamento)
    {
        $qb = $this->createQueryBuilder('af');
        
        return $qb->select('af')
            ->innerJoin('af.projetoFolhaPagamento', 'pfp')
            ->innerJoin('pfp.folhaPagamento', 'fp')
            ->where('pfp.folhaPagamento = :folhaPagamento')
            ->andWhere('af.stRegistroAtivo = :stAtivo')
            ->andWhere('pfp.stRegistroAtivo = :stAtivo')
            ->andWhere('fp.stRegistroAtivo = :stAtivo')
            ->andWhere('af.detalheArquivoRetornoPagamento IS NULL')
            ->setParameter('folhaPagamento', $folhaPagamento)
            ->setParameter('stAtivo', 'S')
            ->getQuery()
            ->getResult();
    }

    /**
     * 
     * @param ParameterBag $pb
     * @return array
     */
    public function listByParameters(ParameterBag $pb)
    {
        $qb = $this->createQueryBuilder('af');
        
        $qb->select('af')
            ->innerJoin('af.projetoFolhaPagamento', 'pfp')
            ->innerJoin('pfp.projeto', 'proj')
            ->innerJoin('af.projetoPessoa', 'pp')
            ->innerJoin('pp.pessoaPerfil', 'pperf')
            ->innerJoin('pperf.pessoaFisica', 'pf')
            ->innerJoin('pf.pessoa', 'p')
            ->innerJoin('pperf.perfil', 'perf')            
            ->where('af.stRegistroAtivo = :stAtivo')
            ->andWhere('pfp.stRegistroAtivo = :stAtivo')
            ->andWhere('af.stParecer = :stParecer')
            ->orderBy('p.noPessoa')
            ->setParameter('stAtivo', 'S')
            ->setParameter('stParecer', 'S');
        
        if ($pb->get('folhaPagamento')) {
            $qb->andWhere('pfp.folhaPagamento = :folhaPagamento')
                ->setParameter('folhaPagamento', $pb->get('folhaPagamento'));
        }        
        if ($pb->get('notExistsInFolha')) {
            $this->notExistsInFolha($qb, $pb);
        }
//        if ($pb->has('issueRetorno')) {
//            $this->issueRetorno($qb);
//        }

        
        return $qb->getQuery()->getResult();
    }
    
    /**
     * 
     * @param QueryBuilder $qb
     */
    private function issueRetorno(QueryBuilder $qb)
    {
        $qb->innerJoin('af.detalheArquivoRetornoPagamento', 'dar')
            ->andWhere('dar.nuSituacaoCredito <> :sitCredito')
            ->setParameter('sitCredito', DicionarioCpb::$dicionario['SIT-CMD']['values']['Registro OK']);
    }
    
    /**
     * 
     * @param QueryBuilder $qb
     * @param ParameterBag $pb
     */
    private function notExistsInFolha(QueryBuilder $qb, ParameterBag $pb)
    {
        $qb->andWhere($qb->expr()->not($qb->expr()->exists(
            'SELECT 1 FROM App:AutorizacaoFolha af2
            JOIN af2.projetoFolhaPagamento pfp2
            WHERE af2.stRegistroAtivo = :stAtivo
            AND pfp2.stRegistroAtivo = :stAtivo
            AND pfp2.folhaPagamento = :notExistsInFolha
            AND af2.projetoPessoa = af.projetoPessoa'
        )))->setParameter('notExistsInFolha', $pb->get('notExistsInFolha'));
    }    
    
    /**
     * 
     * @param mixed $folhaPagamento
     */
    public function findByFolha($folhaPagamento)
    {
        $qb = $this->createQueryBuilder('af');
        
        return $qb->select('af')
            ->innerJoin('af.projetoFolhaPagamento', 'pfp')
            ->where('pfp.folhaPagamento = :folhaPagamento')
            ->andWhere('af.stRegistroAtivo = :stAtivo')
            ->setParameters([
                'folhaPagamento' => $folhaPagamento,
                'stAtivo' => 'S',
            ])->getQuery()->getResult();
    }
}
