<?php

namespace App\CommandBus;

use App\CommandBus\HomologarFolhaPagamentoCommand;
use App\Repository\FolhaPagamentoRepository;
use App\Repository\ProjetoFolhaPagamentoRepository;
use App\Repository\SituacaoFolhaRepository;
use App\Repository\SituacaoProjetoFolhaRepository;
use App\Entity\SituacaoProjetoFolha;
use App\Entity\SituacaoFolha;
use App\Entity\TramitacaoSituacaoFolha;

class HomologarFolhaPagamentoHandler
{
    /**
     * @var FolhaPagamentoRepository
     */
    private $folhaPagamentoRepository;
    
    /**
     * @var ProjetoFolhaPagamentoRepository
     */
    private $projetoFolhaPagamentoRepository;
    
    /**
     * @var SituacaoFolhaRepository
     */
    private $situacaoFolhaRepository;
    
    /**
     * @var SituacaoProjetoFolhaRepository
     */
    private $situacaoProjetoFolhaRepository;
    
    /**
     * @param FolhaPagamentoRepository $folhaPagamentoRepository
     * @param SituacaoFolhaRepository $situacaoFolhaRepository
     */
    public function __construct(
        FolhaPagamentoRepository $folhaPagamentoRepository, 
        ProjetoFolhaPagamentoRepository $projetoFolhaPagamentoRepository,
        SituacaoFolhaRepository $situacaoFolhaRepository,
        SituacaoProjetoFolhaRepository $situacaoProjetoFolhaRepository
    ) {
        $this->folhaPagamentoRepository = $folhaPagamentoRepository;
        $this->projetoFolhaPagamentoRepository = $projetoFolhaPagamentoRepository;
        $this->situacaoFolhaRepository = $situacaoFolhaRepository;
        $this->situacaoProjetoFolhaRepository = $situacaoProjetoFolhaRepository;
    }

    /**
     * @param HomologarFolhaPagamentoCommand $command
     */
    public function handle(HomologarFolhaPagamentoCommand $command)
    {
        $folhasProjetos = $command->getFolhasProjetos();
        
        $situacaoFolhaProjetoHomologada = $this->situacaoProjetoFolhaRepository->find(SituacaoProjetoFolha::HOMOLOGADA);
        $situacaoFolhaProjetoCancelada  = $this->situacaoProjetoFolhaRepository->find(SituacaoProjetoFolha::CANCELADA);
        $situacaoFolhaHomologada        = $this->situacaoFolhaRepository->find(SituacaoFolha::HOMOLOGADA);
        
        foreach ($folhasProjetos as $id => $stParecer) {
            
            $projetoFolha = $this->projetoFolhaPagamentoRepository->find($id);
            
            if ($stParecer == 'S') {
                $projetoFolha->aprovar();
                $projetoFolha->setSituacao($situacaoFolhaProjetoHomologada);
                
            } elseif ($stParecer == 'N') {
                $projetoFolha->reprovar();
                $projetoFolha->setSituacao($situacaoFolhaProjetoCancelada);
                
            } else {
                continue;
            }
            
            $this->projetoFolhaPagamentoRepository->add($projetoFolha);
        }
        
        $folha = $this->folhaPagamentoRepository->find($command->getCoFolhaPagamento());
        
        if (!$folha->hasProjetosPendentesHomologacao()) {
            $folha->setSituacao($situacaoFolhaHomologada);
            $folha->calcularValorTotal();
            
            $tramitacaoSituacaoFolha = new TramitacaoSituacaoFolha($folha, $situacaoFolhaHomologada, $command->getPessoaPerfilAtor());
            $folha->addTramitacaoSituacao($tramitacaoSituacaoFolha);
            
            $this->folhaPagamentoRepository->add($folha);
        }
    }
}