<?php

namespace App\CommandBus;

use App\CommandBus\InformarOrdemBancariaCommand;
use App\Repository\FolhaPagamentoRepository;
use App\Repository\SituacaoFolhaRepository;
use App\Repository\ProjetoFolhaPagamentoRepository;
use App\Repository\SituacaoProjetoFolhaRepository;
use App\Entity\SituacaoFolha;
use App\Entity\SituacaoProjetoFolha;
use App\Entity\TramitacaoSituacaoFolha;

class InformarOrdemBancariaHandler
{
    /**
     * @var FolhaPagamentoRepository
     */
    private $folhaPagamentoRepository;
    
    /**
     * @var SituacaoFolhaRepository
     */
    private $situacaoFolhaRepository;
    
    /**
     * @var ProjetoFolhaPagamentoRepository
     */
    private $projetoFolhaPagamentoRepository;
    
    /**
     * @var SituacaoProjetoFolhaRepository
     */
    private $situacaoProjetoFolhaRepository;
    
    /**
     * @param FolhaPagamentoRepository $folhaPagamentoRepository
     * @param SituacaoFolhaRepository $situacaoFolhaRepository
     * @param ProjetoFolhaPagamentoRepository $ProjetoFolhaPagamentoRepository
     * @param SituacaoProjetoFolhaRepository $situacaoProjetoFolhaRepository
     */
    public function __construct(
        FolhaPagamentoRepository $folhaPagamentoRepository, 
        SituacaoFolhaRepository $situacaoFolhaRepository,
        ProjetoFolhaPagamentoRepository $projetoFolhaPagamentoRepository,
        SituacaoProjetoFolhaRepository $situacaoProjetoFolhaRepository
    ) {
        $this->folhaPagamentoRepository = $folhaPagamentoRepository;
        $this->situacaoFolhaRepository = $situacaoFolhaRepository;
        $this->projetoFolhaPagamentoRepository = $projetoFolhaPagamentoRepository;
        $this->situacaoProjetoFolhaRepository = $situacaoProjetoFolhaRepository;
    }

    /**
     * @param InformarOrdemBancariaCommand $command
     */
    public function handle(InformarOrdemBancariaCommand $command)
    {
        $situacaoEnviadaParaFundo = $this->situacaoFolhaRepository->find(SituacaoFolha::ORDEM_BANCARIA_EMITIDA);
        
        $folha = $command->getFolhaPagamento();
        $folha->setSituacao($situacaoEnviadaParaFundo);
        $folha->setNuOrdemBancaria($command->getNuOrdemBancaria());
        
        $tramitacaoSituacaoFolha = new TramitacaoSituacaoFolha($folha, $situacaoEnviadaParaFundo, $command->getPessoaPerfilAtor());
        $folha->addTramitacaoSituacao($tramitacaoSituacaoFolha);
        
        $this->folhaPagamentoRepository->add($folha);
        
        $situacaoEnviadaParaPagamento = $this->situacaoProjetoFolhaRepository->find(SituacaoProjetoFolha::ORDEM_BANCARIA_EMITIDA);
        
        foreach($folha->getProjetosFolhaPagamentoHomologadas() as $folhaHomologada) {
            $folhaHomologada->setSituacao($situacaoEnviadaParaPagamento);
            $this->projetoFolhaPagamentoRepository->add($folhaHomologada);
        }
    }
}