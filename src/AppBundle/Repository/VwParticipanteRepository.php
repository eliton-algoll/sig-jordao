<?php

namespace AppBundle\Repository;

use Symfony\Component\HttpFoundation\ParameterBag;
use Doctrine\ORM\Query\Expr\Join;

class VwParticipanteRepository extends RepositoryAbstract
{
    public function searchRelatorioParticipante(ParameterBag $params)
    {
        $queryParams = $queryTypes = [];
        $queryParamsStr = '';
        
        if($params->get('nuSipar')) {
            $queryParamsStr .= ' AND VW.NU_SIPAR LIKE ? ';
            $queryParams[] = '%' . trim($params->get('nuSipar')) . '%';
            $queryTypes[]  = \PDO::PARAM_STR;
        }
        if($params->get('nuCpf')) {
            $queryParamsStr .= ' AND VW.NU_CPF_CNPJ_PESSOA like ? ';
            $queryParams[] = '%' . $params->getAlnum('nuCpf') . '%';
            $queryTypes[]  = \PDO::PARAM_STR;
        }
        if($params->get('noPessoa')) {
            $queryParamsStr .= ' AND VW.NO_PESSOA LIKE upper(?) ';
            $queryParams[] = '%' . trim(strtoupper($params->get('noPessoa'))) . '%';
            $queryTypes[]  = \PDO::PARAM_STR;
        }
        if($params->get('perfil')) {
            $queryParamsStr .= ' AND VW.CO_SEQ_PERFIL = ? ';
            $queryParams[] = $params->get('perfil');
            $queryTypes[]  = \PDO::PARAM_INT;
        }
        if($params->get('stVoluntarioProjeto')) {
            $queryParamsStr .= ' AND VW.ST_VOLUNTARIO_PROJETO = ? ';
            $queryParams[] = $params->get('stVoluntarioProjeto');
            $queryTypes[]  = \PDO::PARAM_STR;
        }
        if($params->get('grupoAtuacao')) {
            $queryParamsStr .= ' AND GA.NO_GRUPO_ATUACAO LIKE ? ';
            $queryParams[] = '%' . $params->get('grupoAtuacao') . '%';
            $queryTypes[]  = \PDO::PARAM_STR;
        }
        if($params->get('cursoGraduacao')) {
            $queryParamsStr .= ' AND VW.CURSO_GRADUACAO LIKE ? ';
            $queryParams[] = '%' . $params->get('cursoGraduacao') . '%';
            $queryTypes[]  = \PDO::PARAM_STR;
        }

        $query = <<<'SQL'
                
            SELECT 
                VW.NU_SIPAR,
                VW.DS_PERFIL,
                VW.NO_PESSOA,
                VW.NU_CPF_CNPJ_PESSOA,
                VW.DS_EMAIL,
                VW.CURSO_GRADUACAO,
                VW.ST_VOLUNTARIO_PROJETO,
                VW.DT_INCLUSAO_PROJPESSOA,
                LISTAGG(GA.NO_GRUPO_ATUACAO, ', ') 
                    WITHIN GROUP( ORDER BY GA.NO_GRUPO_ATUACAO) AS "NO_GRUPO_ATUACAO"
            FROM DBPET.VW_PARTICIPANTE VW
            LEFT JOIN DBPET.RL_PROJETOPESSOA_GRUPOATUACAO PRPGA
                ON PRPGA.CO_PROJETO_PESSOA = VW.CO_SEQ_PROJETO_PESSOA
                AND PRPGA.ST_REGISTRO_ATIVO = 'S'
            LEFT JOIN DBPET.TB_GRUPO_ATUACAO GA 
                ON GA.CO_SEQ_GRUPO_ATUACAO = PRPGA.CO_GRUPO_ATUACAO
                AND GA.ST_REGISTRO_ATIVO = 'S'
            WHERE VW.ST_REGISTROATIVO_PROJPESSOA = 'S'
            AND VW.ST_REGISTROATIVO_PESPERFIL = 'S'
SQL;
        
        $groupBy = <<<'SQL'
            GROUP BY VW.NU_CPF_CNPJ_PESSOA,
                VW.NU_SIPAR,
                VW.DS_PERFIL,
                VW.NO_PESSOA,
                VW.CURSO_GRADUACAO,
                VW.ST_VOLUNTARIO_PROJETO,
                VW.DT_INCLUSAO_PROJPESSOA,
                VW.DS_EMAIL
SQL;
        
        $orderByClausule = '';
        if($params->get('order-by') && $params->get('sort')) {
            $orderByClausule = ' ORDER BY ' . $params->get('order-by') . ' ' . $params->get('sort');
        }
        
                
        
        $stmt = $this->_em->getConnection()->executeQuery(
            $query . $queryParamsStr . $groupBy . $orderByClausule, 
            $queryParams, 
            $queryTypes
        ); 
        
        return $stmt->fetchAll();
    }
    
    /**
     * 
     * @param ParameterBag $pb
     * @return type
     */
    public function search(ParameterBag $pb)
    {
        $qb = $this->createQueryBuilder('p');
        
        if ($pb->get('coProjeto')) {
            $qb->andWhere('p.coSeqProjeto = :projeto')
                ->setParameter('projeto', $pb->get('coProjeto'));
        }
        if ($pb->get('nuSipar')) {
            $qb->andWhere('p.nuSipar = :nuSipar')
                ->setParameter('nuSipar', $pb->get('nuSipar'));
        }
        if ($pb->get('tipoParticipante')) {
            $qb->andWhere('p.coSeqPerfil = :perfil')
                ->setParameter('perfil', $pb->get('tipoParticipante'));
        }
        if ($pb->get('noPessoa')) {
            $qb->andWhere('p.noPessoa LIKE :noPessoa')
                ->setParameter('noPessoa', $pb->get('noPessoa') . '%');
        }
        if ($pb->get('nuCpf')) {
            $qb->andWhere('p.nuCpfCnpjPessoa = :nuCpf')
                ->setParameter('nuCpf', preg_replace('/[^0-9]/', '', $pb->get('nuCpf')));
        }
        
        $this->orderPagination($qb, $pb);
        
        return $qb->getQuery();
    }
    
    /**
     * 
     * @param ParameterBag $pb
     * @return array
     */
    public function searchRelatorioGerencial(ParameterBag $pb)
    {
        $sql = <<<SQL
                SELECT *
                    FROM   (SELECT F.DS_PROGRAMA
                                   || ' - '
                                   || C.DS_PUBLICACAO                               AS
                                   PROGRAMA_PUBLICACAO,
                                   A.NU_SIPAR,
                                   CASE
                                     WHEN F.TP_PROGRAMA = 1 THEN E.GRUPO_ATUACAO_PROJETO
                                     ELSE I.NO_GRUPO_ATUACAO
                                   END                                              AS
                                          GRUPO_ATUACAO_PROJETO,
                                   I.TIPO_AREA_TEMATICA                             AS AREA_ATUACAO_PROJETO,
                                   I.CO_SEQ_GRUPO_ATUACAO,
                                   A.NO_INSTITUICAO_PROJETO,
                                   A.SECRETARIA_SAUDE,
                                   A.NO_PESSOA,
                                   A.CO_BANCO,
                                   A.NO_BANCO,
                                   A.CO_AGENCIA,
                                   DP.CO_CONTA,
                                   A.NU_ANO_INGRESSO,
                                   A.DS_CATEGORIA_PROFISSIONAL,
                                   A.CURSO_GRADUACAO,
                                   A.NU_CPF_CNPJ_PESSOA,
                                   TO_CHAR(A.DT_INCLUSAO_PROJPESSOA, 'DD/MM/YYYY')  AS
                                          DT_INCLUSAO_PROJPESSOA,
                                   TO_CHAR(A.DT_NASCIMENTO, 'DD/MM/YYYY')           AS DT_NASCIMENTO
                                   ,
                                   A.NO_LOGRADOURO
                                   || ', '
                                   || A.NU_LOGRADOURO
                                   || ', '
                                   || A.DS_COMPLEMENTO
                                   || ', '
                                   || A.NU_CEP                                      AS ENDERECO,
                                   A.DS_EMAIL,
                                   D.SG_SEXO,
                                   DECODE(A.ST_REGISTROATIVO_PROJPESSOA, 'S', 'Ativo',
                                                                         'Inativo') AS
                                   TIPO_PARTICIPACAO,
                                   A.ST_REGISTROATIVO_PROJPESSOA,
                                   G.TELEFONE,
                                   A.CO_SEQ_PERFIL,
                                   A.DS_PERFIL,
                                   DECODE(A.ST_VOLUNTARIO_PROJETO, 'S', 'Voluntário',
                                                                   'Bolsista')      AS
                                          ST_VOLUNTARIO_PROJETO,
                                   C.CO_SEQ_PUBLICACAO,
                                   A.CO_SEQ_PROJETO,
                                   H.CO_CNES,
                                   TO_CHAR(A.DT_DESLIGAMENTO, 'DD/MM/YYYY')         AS
                                   DT_DESLIGAMENTO
                            FROM   DBPET.VW_PARTICIPANTE A
                                   LEFT JOIN DBPET.TB_DADO_PESSOAL DP
                                           ON A.NU_CPF_CNPJ_PESSOA = DP.NU_CPF
                                   INNER JOIN DBPET.TB_PROJETO B
                                           ON A.CO_SEQ_PROJETO = B.CO_SEQ_PROJETO
                                   INNER JOIN DBPET.TB_PUBLICACAO C
                                           ON B.CO_PUBLICACAO = C.CO_SEQ_PUBLICACAO
                                   INNER JOIN DBPESSOA.TB_PESSOA_FISICA D
                                           ON A.NU_CPF_CNPJ_PESSOA = D.NU_CPF
                                   INNER JOIN DBPET.TB_PROGRAMA F
                                           ON C.CO_PROGRAMA = F.CO_SEQ_PROGRAMA
                                   LEFT JOIN (SELECT E_A.CO_PROJETO_PESSOA,
                                                     LISTAGG(E_B.NO_GRUPO_ATUACAO, ', ' ON OVERFLOW TRUNCATE)
                                                       WITHIN GROUP( ORDER BY E_B.NO_GRUPO_ATUACAO)
                                                     AS
                                                     GRUPO_ATUACAO_PROJETO
                                              FROM   DBPET.RL_PROJETOPESSOA_GRUPOATUACAO E_A
                                                     INNER JOIN DBPET.TB_GRUPO_ATUACAO E_B
                                                             ON E_A.CO_GRUPO_ATUACAO =
                                                                E_B.CO_SEQ_GRUPO_ATUACAO
                                              WHERE  E_A.ST_REGISTRO_ATIVO = 'S'
                                              GROUP  BY E_A.CO_PROJETO_PESSOA) E
                                          ON A.CO_SEQ_PROJETO_PESSOA = E.CO_PROJETO_PESSOA
                                             AND F.TP_PROGRAMA = 1
                                   LEFT JOIN (SELECT G_A.NU_CPF_CNPJ_PESSOA,
                                                     LISTAGG('('
                                                             || TO_NUMBER(G_A.NU_DDD)
                                                             || ') '
                                                             || G_A.NU_TELEFONE, ', ' ON OVERFLOW TRUNCATE)
                                                       WITHIN GROUP( ORDER BY G_A.NU_TELEFONE) AS
                                                     TELEFONE
                                              FROM   DBPET.TB_TELEFONE G_A
                                              GROUP  BY G_A.NU_CPF_CNPJ_PESSOA) G
                                          ON G.NU_CPF_CNPJ_PESSOA = A.NU_CPF_CNPJ_PESSOA
                                   LEFT JOIN DBPET.TB_DADO_ACADEMICO H
                                          ON A.CO_SEQ_PROJETO_PESSOA = H.CO_PROJETO_PESSOA
                                             AND H.ST_REGISTRO_ATIVO = 'S'
                                   LEFT JOIN (SELECT
                                                     T1.CO_SEQ_GRUPO_ATUACAO,
                                                     T1.NO_GRUPO_ATUACAO,
                                                     T2.CO_PROJETO_PESSOA,
                                                     LISTAGG(T5.DS_TIPO_AREA_TEMATICA, ', ' ON OVERFLOW TRUNCATE)
                                                       WITHIN GROUP(ORDER BY
                                                       T5.DS_TIPO_AREA_TEMATICA) AS
                                                                           TIPO_AREA_TEMATICA
                                              FROM   DBPET.TB_GRUPO_ATUACAO T1
                                                     INNER JOIN DBPET.RL_PROJETOPESSOA_GRUPOATUACAO
                                                                T2
                                                             ON T1.CO_SEQ_GRUPO_ATUACAO =
                                                                T2.CO_GRUPO_ATUACAO
                                                     LEFT JOIN DBPET.RL_PROJPES_GRPATUAC_AREATEM T3
                                                            ON T2.CO_SEQ_PROJPES_GRUPOATUAC =
                                                               T3.CO_PROJPES_GRUPOATUAC
                                                               AND T3.ST_REGISTRO_ATIVO = 'S'
                                                     LEFT JOIN DBPET.TB_AREA_TEMATICA T4
                                                            ON T3.CO_AREA_TEMATICA =
                                                               T4.CO_SEQ_AREA_TEMATICA
                                                               AND T4.ST_REGISTRO_ATIVO = 'S'
                                                     LEFT JOIN DBPET.TB_TIPO_AREA_TEMATICA T5
                                                            ON T4.CO_TIPO_AREA_TEMATICA =
                                                               T5.CO_SEQ_TIPO_AREA_TEMATICA
                                              WHERE  T2.ST_REGISTRO_ATIVO = 'S'
                                              GROUP  BY T1.CO_SEQ_GRUPO_ATUACAO,
                                                        T1.NO_GRUPO_ATUACAO,
                                                        T2.CO_PROJETO_PESSOA) I
                                          ON A.CO_SEQ_PROJETO_PESSOA = I.CO_PROJETO_PESSOA
                                             AND F.TP_PROGRAMA = 2)
                    WHERE  1 = 1
SQL;
        
        $parameters = new \stdClass();
        $parameters->sql = $parameters->values = $parameters->types = [];
        
        if ($pb->get('to_customizacao')) {
            $sql = str_replace('*', implode(',', $pb->get('to_customizacao')), $sql);
        }
        if ($pb->get('publicacao')) {
            $parameters->sql[] = "CO_SEQ_PUBLICACAO = ?";
            $parameters->values[] = $pb->get('publicacao');
            $parameters->types[] = \PDO::PARAM_INT;
        }
        if ($pb->get('projeto')) {
            $parameters->sql[] = "CO_SEQ_PROJETO = ?";
            $parameters->values[] = $pb->get('projeto');
            $parameters->types[] = \PDO::PARAM_INT;
        }
        if ($pb->get('nuSipar')) {
            $parameters->sql[] = "NU_SIPAR = ?";
            $parameters->values[] = $pb->get('nuSipar');
            $parameters->types[] = \PDO::PARAM_STR;
        }
        if ($pb->get('tipoParticipante')) {
            $parameters->sql[] = "CO_SEQ_PERFIL = ?";
            $parameters->values[] = $pb->get('tipoParticipante');
            $parameters->types[] = \PDO::PARAM_INT;
        }
        if ($pb->get('tipoParticipacao')) {
            $parameters->sql[] = "ST_VOLUNTARIO_PROJETO = ?";
            $parameters->values[] = $pb->get('tipoParticipante');
            $parameters->types[] = \PDO::PARAM_STR;
        }
        if ($pb->get('stParticipante')) {
            $parameters->sql[] = "ST_REGISTROATIVO_PROJPESSOA = ?";
            $parameters->values[] = $pb->get('stParticipante');
            $parameters->types[] = \PDO::PARAM_STR;
        }
        if ($pb->get('grupoTutorial')) {
            $parameters->sql[] = "CO_SEQ_GRUPO_ATUACAO = ?";
            $parameters->values[] = $pb->get('grupoTutorial');
            $parameters->types[] = \PDO::PARAM_INT;
        }
        
        $sql .= ($parameters->sql) ? ' AND ' . implode(' AND ', $parameters->sql) : null;
        
        $conn = $this->getEntityManager()->getConnection();
        return $conn->executeQuery($sql, $parameters->values, $parameters->types)->fetchAll();
    }

}
