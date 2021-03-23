<?php

namespace AppBundle\Query;

use Symfony\Component\HttpFoundation\ParameterBag;
use Knp\Component\Pager\Paginator;
use AppBundle\Repository\RetornoPagamentoRepository;
use AppBundle\Entity\RetornoPagamento;

final class RetornoPagamentoQuery
{
    /**
     *
     * @var Paginator 
     */
    private $paginator;
    
    /**
     *
     * @var RetornoPagamentoRepository 
     */
    private $retornoPagamentoRepository;
    
    /**
     * 
     * @param Paginator $paginator
     * @param RetornoPagamentoRepository $retornoPagamentoRepository
     */
    public function __construct(
        Paginator $paginator,
        RetornoPagamentoRepository $retornoPagamentoRepository
    ) {
        $this->paginator = $paginator;
        $this->retornoPagamentoRepository = $retornoPagamentoRepository;
    }

        /**
     * 
     * @param ParameterBag $pb
     * @return type
     */
    public function searchAll(ParameterBag $pb)
    {
        return $this->paginator->paginate(
            $this->retornoPagamentoRepository->search($pb),
            $pb->getInt('page', 1),
            $pb->getInt('limit', 10)
        );
    }
    
    /**
     * 
     * @param RetornoPagamento $retornoPagamento
     * @param ParameterBag $pb
     * @return array
     */
    public function report(RetornoPagamento $retornoPagamento, ParameterBag $pb)
    {
        $result = $this->retornoPagamentoRepository->report($retornoPagamento, $pb);
        
        $report = new \stdClass();
        $report->status = false;
        
        if ($result) {
            
            $report->status = true;
            $report->programa = $result['folhaPagamento']['publicacao']['programa']['dsPrograma'];
            $report->referencia = $result['folhaPagamento']['nuMes'] . '/' . $result['folhaPagamento']['nuAno'];
            $report->dtRecepcao = $result['dtInclusao'];
            $report->noArquivoOriginal = $result['noArquivoOriginal'];
            $report->nuLote = $result['cabecalhoArquivoRetornoPagamento']['nuSequencialLote'];
            $report->vlTotalPago = 0;
            $report->qtTotalParticipante = 0;
            
            $report->groups = [];            
            
            foreach ($result['detalheArquivoRetornoPagamento'] as $detalhe) {                
                $report->groups[$detalhe['nuSituacaoCredito']][] = $detalhe;
                $report->vlTotalPago += $detalhe['vlCredito'];
                $report->qtTotalParticipante++;
            }            
        } 
        
        return $report;
    }
}
