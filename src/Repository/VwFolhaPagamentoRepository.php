<?php

namespace App\Repository;

use \App\Traits\MaskTrait;
use App\Entity\AgenciaBancaria;
use App\Entity\SituacaoProjetoFolha;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\ParameterBag;

class VwFolhaPagamentoRepository extends RepositoryAbstract
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AgenciaBancaria::class);
    }

    private static $RELATORIO_PAGAMENTO = [
        SituacaoProjetoFolha::AUTORIZADA => 'getQueryAutorizadas',
        SituacaoProjetoFolha::HOMOLOGADA => 'getQueryHomologadas'
    ];
    
    public function searchRelatorioPagamento(ParameterBag $params) 
    {
        $queryParams = $queryTypes = [];
        $queryParamsStr = '';

        if($params->getInt('stProjetoFolha')) {
            $queryParamsStr .= ' AND VW.CO_SEQ_SITUACAO_PROJ_FOLHA = ? ';
            $queryParams[] = $params->getInt('stProjetoFolha');
            $queryTypes[]  = \PDO::PARAM_INT;
        }
        
        if($params->get('nuSipar')) {
            $queryParamsStr .= ' AND VW.NU_SIPAR LIKE ? ';
            $queryParams[] = '%' . $params->get('nuSipar') . '%';
            $queryTypes[]  = \PDO::PARAM_STR;
        }
        if($params->get('nuMes')) {
            $queryParamsStr .= ' AND VW.NU_MES = ? ';
            $queryParams[] = $params->get('nuMes');
            $queryTypes[]  = \PDO::PARAM_STR;
        }
        if($params->get('campus')) {
            $queryParamsStr .= ' AND VW.NO_INSTITUICAO_PROJETO LIKE ? ';
            $queryParams[] = '%' . $this->maskcnpj($params->get('campus')) . '%';
            $queryTypes[]  = \PDO::PARAM_STR;
        }
        if($params->get('secretaria')) {
            $queryParamsStr .= ' AND VW.SECRETARIA_SAUDE LIKE ? ';
            $queryParams[] = '%' . $this->maskcnpj($params->get('secretaria')) . '%';
            $queryTypes[]  = \PDO::PARAM_STR;
        }
               
//        $contextualQuery = self::$RELATORIO_PAGAMENTO[$params->getInt('stProjetoFolha',SituacaoProjetoFolha::AUTORIZADA)];
       
        $query = <<<SQL
            SELECT	VW.CO_SEQ_FOLHA_PAGAMENTO,
                VW.CO_SEQ_PROJETO,
                VW.NU_SIPAR,
                VW.NO_PESSOA,
                VW.NU_CPF,
                VW.VL_BOLSA,
                VW.NO_COORD,
                VW.NU_MES||'/'||VW.NU_ANO NU_MES_ANO,
                VW.DT_AUTORIZACAO,
                VW.DS_SITUACAO_PROJETO_FOLHA ST_FOLHA
            FROM DBPET.VW_FOLHA_PAGAMENTO VW           
            WHERE VW.NU_SIPAR IS NOT NULL
SQL;

        //$query = $this->$contextualQuery($queryParamsStr);

        $orderByClausule = ' ORDER BY VW.NU_MES DESC, VW.NU_ANO DESC, VW.NU_SIPAR ASC, VW.NO_PESSOA ASC ';
        if($params->get('order-by')) {
            $orderByClausule.= ' ORDER BY ' . $params->get('order-by') . ' ' . $params->get('sort');
        }

        $stmt = $this->_em->getConnection()->executeQuery(
            $query . $queryParamsStr . $orderByClausule, $queryParams, $queryTypes
        );

       
        return $stmt->fetchAll();
    }
    
    public function getQueryAutorizadas($queryParams)
    {
        $query = <<<SQL
            SELECT 
                PR.CO_SEQ_PROJETO,
                FP.NU_MES,
                PR.NU_SIPAR,
                PR.QT_BOLSA BOLSAS_PERMITIDAS,
                COUNT(VW.NU_CPF) BOLSAS_AUTORIZADAS,
                MIN(VW.DT_AUTORIZACAO) DT_AUTORIZACAO,
                VW.NO_INSTITUICAO_PROJETO,
                VW.SECRETARIA_SAUDE
            FROM DBPET.TB_PROJETO PR
            INNER JOIN DBPET.TB_PROJETO_FOLHAPAGAMENTO PRFP
                ON PR.CO_SEQ_PROJETO = PRFP.CO_PROJETO
                AND PR.ST_REGISTRO_ATIVO = 'S'
                AND PRFP.ST_REGISTRO_ATIVO = 'S'
            INNER JOIN DBPET.TB_FOLHA_PAGAMENTO FP
                ON PRFP.CO_FOLHA_PAGAMENTO = FP.CO_SEQ_FOLHA_PAGAMENTO
                AND FP.ST_REGISTRO_ATIVO = 'S'
            INNER JOIN DBPET.VW_FOLHA_PAGAMENTO VW
                ON PR.CO_SEQ_PROJETO = VW.CO_SEQ_PROJETO
                AND FP.NU_MES = VW.NU_MES
                AND VW.CO_SEQ_SITUACAO_PROJ_FOLHA = ?
SQL;
        
        $groupBy = <<<GROUPBY
          GROUP BY 
            PR.CO_SEQ_PROJETO,
            FP.NU_MES,
            PR.NU_SIPAR,
            PR.QT_BOLSA,
            VW.NO_INSTITUICAO_PROJETO,
            VW.SECRETARIA_SAUDE 
GROUPBY;
        
        return $query . $queryParams . $groupBy;
    }
    
    public function getQueryHomologadas($queryParams)
    {
        $query = <<<SQL
            SELECT
                VW.CO_SEQ_PROJETO, 
                VW.NU_SIPAR, 
                VW.NO_PESSOA, 
                VW.NU_CPF, 
                VW.VL_BOLSA, 
                VW.NO_COORD, 
                VW.DT_AUTORIZACAO
            FROM DBPET.VW_FOLHA_PAGAMENTO VW           
            WHERE VW.NU_SIPAR IS NOT NULL
            AND VW.CO_SEQ_SITUACAO_PROJ_FOLHA = ?
SQL;
        
        return $query . $queryParams;
    }
}
