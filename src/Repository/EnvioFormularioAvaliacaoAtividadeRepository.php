<?php

namespace App\Repository;

use PDO;
use App\Entity\AgenciaBancaria;
use App\Repository\RepositoryAbstract;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\ParameterBag;

class EnvioFormularioAvaliacaoAtividadeRepository extends RepositoryAbstract
{
  public function __construct(ManagerRegistry $registry)
  {
      parent::__construct($registry, AgenciaBancaria::class);
  }
  
    /**
     * 
     * @param ParameterBag $pb
     * @return array
     */
    public function searchEnvio(ParameterBag $pb)
    {
        $sql = " 
                SELECT  A.CO_SEQ_ENVIO_FORM_AVAL_ATIVID,
                        A.DT_INCLUSAO,
                        A.DT_INICIO_PERIODO,
                        A.DT_FIM_PERIODO,
                        A.ST_FINALIZADO,
                        B.NO_FORMULARIO,
                        B.DS_URL_FORMULARIO,
                        B.NO_ARQUIVO_FORMULARIO,
                        C.QT_PENDENTES,
                        C.QT_FINALIZADOS,
                        C.QT_N_PENDENTES,
                        COUNT(D.CO_SEQ_TRAMITACAO_FORMULARIO) AS QT_ENVIO,
                        E.PERFIS
                      FROM DBPET.TB_ENVIO_FORM_AVALIACAO_ATIVID A
                      INNER JOIN DBPET.TB_FORMULARIO_AVALIACAO_ATIVID B
                      ON A.CO_FORM_AVALIACAO_ATIVD = B.CO_SEQ_FORM_AVALIACAO_ATIVD
                      INNER JOIN
                        (SELECT CO_ENVIO_FORM_AVAL_ATIVID,
                          SUM(QT_PENDENTES)   AS QT_PENDENTES,
                          SUM(QT_FINALIZADOS) AS QT_FINALIZADOS,
                          SUM(QT_N_PENDENTES) AS QT_N_PENDENTES
                        FROM
                          (SELECT T1.CO_ENVIO_FORM_AVAL_ATIVID,
                            COALESCE(DECODE(T1.CO_SITUACAO_TRAMITE_FORM, 4, 0, COUNT(T1.CO_SEQ_TRAMITACAO_FORMULARIO)),0) AS QT_PENDENTES,
                            COALESCE(DECODE(T1.CO_SITUACAO_TRAMITE_FORM, 4, COUNT(T1.CO_SEQ_TRAMITACAO_FORMULARIO)),0)    AS QT_FINALIZADOS,
                            COALESCE(DECODE(T1.CO_SITUACAO_TRAMITE_FORM, 1, 0, COUNT(T1.CO_SEQ_TRAMITACAO_FORMULARIO)),0)    AS QT_N_PENDENTES
                          FROM DBPET.TB_TRAMITACAO_FORMULARIO T1                          
                          WHERE T1.ST_REGISTRO_ATIVO = 'S'                          
                          GROUP BY T1.CO_ENVIO_FORM_AVAL_ATIVID,
                            T1.CO_SITUACAO_TRAMITE_FORM
                          )
                        GROUP BY CO_ENVIO_FORM_AVAL_ATIVID
                        ) C
                      ON A.CO_SEQ_ENVIO_FORM_AVAL_ATIVID = C.CO_ENVIO_FORM_AVAL_ATIVID
                      INNER JOIN DBPET.TB_TRAMITACAO_FORMULARIO D
                      ON A.CO_SEQ_ENVIO_FORM_AVAL_ATIVID = D.CO_ENVIO_FORM_AVAL_ATIVID
                      INNER JOIN
                        (SELECT T1.CO_FORM_AVALIACAO_ATIVD,
                          LISTAGG(T2.DS_PERFIL, ', ') WITHIN GROUP(
                        ORDER BY T2.DS_PERFIL) AS PERFIS
                        FROM DBPET.RL_PERFIL_FORMAVALIACAOATIVID T1
                        INNER JOIN DBPET.TB_PERFIL T2
                        ON T1.CO_PERFIL = T2.CO_SEQ_PERFIL
                        GROUP BY T1.CO_FORM_AVALIACAO_ATIVD
                        ) E ON A.CO_FORM_AVALIACAO_ATIVD = E.CO_FORM_AVALIACAO_ATIVD
                      INNER JOIN DBPET.TB_PROJETO_PESSOA F
                      ON D.CO_PROJETO_PESSOA = F.CO_SEQ_PROJETO_PESSOA
                      INNER JOIN DBPET.TB_PESSOA_PERFIL G
                      ON G.CO_SEQ_PESSOA_PERFIL = F.CO_PESSOA_PERFIL
                      INNER JOIN DBPET.TB_PROJETO H
                      ON F.CO_PROJETO = H.CO_SEQ_PROJETO
                      WHERE D.ST_REGISTRO_ATIVO = 'S' AND A.ST_REGISTRO_ATIVO = 'S' AND B.ST_REGISTRO_ATIVO = 'S'
                      @criteria@
                      GROUP BY A.CO_SEQ_ENVIO_FORM_AVAL_ATIVID,
                        A.DT_INCLUSAO,
                        A.DT_INICIO_PERIODO,
                        A.DT_FIM_PERIODO,
                        A.ST_FINALIZADO,
                        B.NO_FORMULARIO,
                        B.DS_URL_FORMULARIO,
                        B.NO_ARQUIVO_FORMULARIO,
                        C.QT_PENDENTES,
                        C.QT_FINALIZADOS,
                        C.QT_N_PENDENTES,
                        E.PERFIS";
        
        $criteria = $subcriteria = $parameters = $types = [];
        
        if ($pb->get('formularioAvaliacaoAtividade')) {
            $criteria[] = "B.CO_SEQ_FORM_AVALIACAO_ATIVD = :coFormularioAvaliacaoAtividade";
            $parameters['coFormularioAvaliacaoAtividade'] = $pb->get('formularioAvaliacaoAtividade');
            $types['coFormularioAvaliacaoAtividade'] = \PDO::PARAM_INT;
        }
        if ($pb->get('stFinalizado')) {
            $criteria[] = "A.ST_FINALIZADO = :stFinalizado";
            $parameters['stFinalizado'] = $pb->get('stFinalizado');
            $types['stFinalizado'] = \PDO::PARAM_STR;
        }       
        
        $this->setCriteriaToSQL($sql, $criteria, 'AND');
        $this->orderPaginationSQL($sql, $pb, 'A.DT_INICIO_PERIODO DESC');
        
        return $this
            ->getEntityManager()
            ->getConnection()
            ->executeQuery($sql, $parameters, $types)
            ->fetchAll();
    }
}
