<?php

namespace App\Report;

use Symfony\Component\HttpFoundation\ParameterBag;
use App\Repository\FolhaPagamentoRepository;

class FolhaPagamentoDetalhadoReport
{
    /**
     *
     * @var FolhaPagamentoRepository 
     */
    private $folhaPagamentoRepository;
    
    /**
     *
     * @var array
     */
    private $report = [];
    
    /**
     * 
     * @param FolhaPagamentoRepository $folhaPagamentoRepository
     */
    public function __construct(FolhaPagamentoRepository $folhaPagamentoRepository)
    {
        $this->folhaPagamentoRepository = $folhaPagamentoRepository;
    }
    
    /**
     * 
     * @param ParameterBag $pb
     */
    private function generate(ParameterBag $pb)
    {
        $rawData = $this->folhaPagamentoRepository->relatorioDetalhado($pb);
        
        if (!$rawData) return;
        
        $firstData = current($rawData);
        
        $this->report['folhaPagamento'] = $this->folhaPagamentoRepository->find($firstData['fp_coSeqFolhaPagamento']);
        
        foreach ($rawData as $row) {
            $this->addProjeto($row);
            $this->addParticipante($row);
        }
        
        $this->calculate();
    }  
    
    private function calculate()
    {
        $this->report['vlTotalBolsa'] = $this->report['qtParticipantes'] = 0;
        
        foreach ($this->report['projetos'] as &$projeto) {
            $projeto['vlTotalProjeto'] = array_sum(array_map(function ($participante) {
                return $participante['vlBolsa'];
            }, $projeto['participantes']));
            
            $this->report['vlTotalBolsa'] += $projeto['vlTotalProjeto'];            
            $this->report['qtParticipantes'] += count($projeto['participantes']);
        }
    }
    
    /**
     * 
     * @param array $data
     */
    private function addProjeto(array $data)
    {
        if (!isset($this->report['projetos'][$data['pfp_coSeqProjFolhaPagam']])) {
            $this->report['projetos'][$data['pfp_coSeqProjFolhaPagam']] = [
                'nuSipar' => $data['proj_nuSipar'],
                'noCoordenador' => $data['pc_noPessoa'],
                'dtInclusao' => $data['pfp_dtInclusao'],
                'dsJustificativa' => $data['pfp_dsJustificativa'],
                'stDeclaracao' => $data['pfp_stDeclaracao'] === 'S',
                'participantes' => [],
            ];
        }
    }
    
    /**
     * 
     * @param array $data
     */
    private function addParticipante($data)
    {
        if (isset($this->report['projetos'][$data['pfp_coSeqProjFolhaPagam']]['participantes'][$data['af_coSeqAutorizacaoFolha']])) {
            $this->report['projetos'][$data['pfp_coSeqProjFolhaPagam']]['participantes'][$data['af_coSeqAutorizacaoFolha']][] = $data['ga_noGrupoAtuacao'];
        }   
        
        $this->report['projetos'][$data['pfp_coSeqProjFolhaPagam']]['participantes'][$data['af_coSeqAutorizacaoFolha']] = [
            'noPessoa' => $data['p_noPessoa'],
            'nuCpf' => $data['p_nuCpfCnpjPessoa'],
            'dsPerfil' => $data['perf_dsPerfil'],
            'vlBolsa' => $data['af_vlBolsa'],
            'grupoAtuacao' => [$data['ga_noGrupoAtuacao']],
        ];
    }
    
    /**
     * 
     * @param ParameterBag $pb
     * @return array
     */
    public function getReport(ParameterBag $pb = null)
    {
        if (!$this->report && $pb) {
            $this->generate($pb);
        }
        
        return $this->report;
    }
}
