<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Projeto;
use AppBundle\Entity\FolhaPagamento;
use Doctrine\Common\Util\Debug;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * ProjetoFolhaPagamentoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProjetoFolhaPagamentoRepository extends RepositoryAbstract
{
    /**
     * @param Projeto $projeto
     * @param FolhaPagamento $folha
     * @return \AppBundle\Entity\ProjetoFolhaPagamento[]
     */
    public function findByProjetoAndFolha(Projeto $projeto, FolhaPagamento $folha)
    {
        return  $this->findOneBy(array(
            'projeto' => $projeto->getCoSeqProjeto(),
            'folhaPagamento' => $folha->getCoSeqFolhaPagamento(),
            'stRegistroAtivo' => 'S'
        ));
    }
    
    /**
     * 
     * @param mixed $projeto
     * @return \AppBundle\Entity\ProjetoFolhaPagamento[]
     */
    public function listMensalByProjeto($projeto)
    {
        $qb = $this->createQueryBuilder('pfp');
        
        return $qb
            ->select('pfp')
            ->innerJoin('pfp.folhaPagamento', 'fp')
            ->where('pfp.stRegistroAtivo = :stAtivo')
            ->andWhere('fp.stRegistroAtivo = :stAtivo')
            ->andWhere('pfp.projeto = :projeto')
            ->andWhere('fp.tpFolhaPagamento = :tpFolha')
            ->orderBy('pfp.dtInclusao', 'DESC')
            ->setParameters([
                'stAtivo' => 'S',
                'projeto' => $projeto,
                'tpFolha' => FolhaPagamento::MENSAL,
            ])->getQuery()->getResult();
    }

    public function informeRendimento(ParameterBag $params)
    {
        $queryParams = $queryTypes = [];
        $queryParamsStr = '';

        if ($params->get('projetoPessoa')) {
            $queryParamsStr .= ' AND F.CO_SEQ_PROJETO_PESSOA = ? ';
            $queryParams[] = $params->get('projetoPessoa');
            $queryTypes[] = \PDO::PARAM_INT;
        }
        if ($params->get('publicacao')) {
            $queryParamsStr .= ' AND PR.CO_SEQ_PROGRAMA = ? ';
            $queryParams[] = $params->get('publicacao');
            $queryTypes[] = \PDO::PARAM_INT;
        }
        if ($params->get('nuAnoBase')) {
            $queryParamsStr .= ' AND A.NU_ANO = ? ';
            $queryParams[] = abs($params->get('nuAnoBase'));
            $queryTypes[] = \PDO::PARAM_INT;
        }
        if ($params->get('dtNascimento')) {
            $queryParamsStr .= " AND TO_CHAR(PF.DT_NASCIMENTO, 'DD/MM/YYYY') = ? ";
            $queryParams[] = $params->get('dtNascimento');
            $queryTypes[] = \PDO::PARAM_STR;
        }
        if ($params->get('nuCpf')) {
            $queryParamsStr .= ' AND G.NU_CPF = ? ';
            $queryParams[] = trim($params->getAlnum('nuCpf'));
            $queryTypes[] = \PDO::PARAM_STR;
        }

        if(!empty($queryParamsStr)){

            $query = <<<'SQL'
                SELECT SUB.CO_SEQ_PROJETO,
                       SUB.CO_SEQ_PROGRAMA,
                       SUB.CO_SEQ_PROJETO_PESSOA,
                       SUB.NO_PESSOA,
                       SUB.NU_CPF,
                       SUB.DT_NASCIMENTO,
                       SUB.NU_ANO,
                       SUB.CO_SEQ_PERFIL,
                       SUB.DS_PERFIL,
                       SUB.ST_VOLUNTARIO_PROJETO,
                       SUB.DS_PROGRAMA,
                       SUM(SUB.VL_BOLSA) VALOR_TOTAL
                  FROM (SELECT C.CO_SEQ_PROJETO,
                               PR.CO_SEQ_PROGRAMA,
                               F.CO_SEQ_PROJETO_PESSOA,
                               I.NO_PESSOA,
                               G.NU_CPF,
                               PF.DT_NASCIMENTO,
                               A.NU_MES,
                               A.NU_ANO,
                               H.CO_SEQ_PERFIL,
                               H.DS_PERFIL,
                               F.ST_VOLUNTARIO_PROJETO,
                               PR.DS_PROGRAMA,
                               E.VL_BOLSA,
                               MAX(A.CO_SEQ_FOLHA_PAGAMENTO) CO_SEQ_FOLHA_PAGAMENTO
                          FROM DBPET.TB_FOLHA_PAGAMENTO A
                         INNER JOIN DBPET.TB_PROJETO_FOLHAPAGAMENTO B
                            ON A.CO_SEQ_FOLHA_PAGAMENTO = B.CO_FOLHA_PAGAMENTO
                           AND A.ST_REGISTRO_ATIVO = 'S'
                         INNER JOIN DBPET.TB_PUBLICACAO PU
                            ON PU.CO_SEQ_PUBLICACAO = A.CO_PUBLICACAO
                           AND PU.ST_REGISTRO_ATIVO = 'S'
                         INNER JOIN DBPET.TB_PROGRAMA PR
                            ON PR.CO_SEQ_PROGRAMA = PU.CO_PROGRAMA
                           AND PR.ST_REGISTRO_ATIVO = 'S'
                         INNER JOIN DBPET.TB_PROJETO C
                            ON B.CO_PROJETO = C.CO_SEQ_PROJETO
                           AND C.ST_REGISTRO_ATIVO = 'S'
                         INNER JOIN DBPET.TB_AUTORIZACAO_FOLHA E
                            ON B.CO_SEQ_PROJ_FOLHA_PAGAM = E.CO_PROJ_FOLHA_PAGAM
                           AND E.ST_REGISTRO_ATIVO = 'S'
                         INNER JOIN DBPET.TB_PROJETO_PESSOA F
                            ON E.CO_PROJETO_PESSOA = F.CO_SEQ_PROJETO_PESSOA
                         INNER JOIN DBPET.TB_PESSOA_PERFIL G
                            ON F.CO_PESSOA_PERFIL = G.CO_SEQ_PESSOA_PERFIL
                         INNER JOIN DBPESSOA.TB_PESSOA_FISICA PF
                            ON PF.NU_CPF = G.NU_CPF
                         INNER JOIN DBPET.TB_PERFIL H
                            ON G.CO_PERFIL = H.CO_SEQ_PERFIL
                         INNER JOIN DBPESSOA.TB_PESSOA I
                            ON G.NU_CPF = I.NU_CPF_CNPJ_PESSOA
                         WHERE B.ST_REGISTRO_ATIVO = 'S'

SQL;

            $groupBy = '  GROUP BY C.CO_SEQ_PROJETO,
                                  PR.CO_SEQ_PROGRAMA,
                                  F.CO_SEQ_PROJETO_PESSOA,
                                  I.NO_PESSOA,
                                  G.NU_CPF,
                                  PF.DT_NASCIMENTO,
                                  A.NU_ANO,
                                  A.NU_MES,
                                  H.CO_SEQ_PERFIL,
                                  H.DS_PERFIL,
                                  E.VL_BOLSA,
                                  F.ST_VOLUNTARIO_PROJETO,
                                  PR.DS_PROGRAMA) SUB
                GROUP BY SUB.CO_SEQ_PROJETO,
                          SUB.CO_SEQ_PROGRAMA,
                          SUB.CO_SEQ_PROJETO_PESSOA,
                          SUB.NO_PESSOA,
                          SUB.NU_CPF,
                          SUB.DT_NASCIMENTO,
                          SUB.NU_ANO,
                          SUB.CO_SEQ_PERFIL,
                          SUB.DS_PERFIL,
                          SUB.ST_VOLUNTARIO_PROJETO,
                          SUB.DS_PROGRAMA ';

            $stmt = $this->_em->getConnection()->executeQuery(
                $query . $queryParamsStr . $groupBy, $queryParams, $queryTypes
            );

            return $stmt->fetchAll();
        }else{
            return array();
        }
    }

}
