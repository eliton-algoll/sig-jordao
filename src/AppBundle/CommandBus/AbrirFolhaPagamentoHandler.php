<?php

namespace AppBundle\CommandBus;

use AppBundle\CommandBus\AbrirFolhaPagamentoCommand;
use AppBundle\Repository\FolhaPagamentoRepository;
use AppBundle\Repository\SituacaoFolhaRepository;
use AppBundle\Repository\PublicacaoRepository;
use AppBundle\Entity\SituacaoFolha;
use AppBundle\Entity\FolhaPagamento;
use AppBundle\Entity\TramitacaoSituacaoFolha;

class AbrirFolhaPagamentoHandler
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
     * @var PublicacaoRepository
     */
    private $publicacaoRepository;
        
    public function __construct(
        FolhaPagamentoRepository $folhaPagamentoRepository,
        SituacaoFolhaRepository $situacaoFolhaPagamentoRepository,
        PublicacaoRepository $publicacaoRepository
    ){
        $this->folhaPagamentoRepository = $folhaPagamentoRepository;
        $this->situacaoFolhaRepository  = $situacaoFolhaPagamentoRepository;
        $this->publicacaoRepository     = $publicacaoRepository;
    }

    /**
     * @param \AppBundle\CommandBus\AbrirFolhaPagamentoCommand $command
     */
    public function handle(AbrirFolhaPagamentoCommand $command)
    {
        $situacaoAberta = $this->situacaoFolhaRepository->find(SituacaoFolha::ABERTA);
        $publicacao     = $this->publicacaoRepository->find($command->getPublicacao());
        
        if($publicacao->hasFolhaPagamentoMensalAberta()) {
            throw new \LogicException('Já existe uma folha de pagamento aberta nesta publicação.');
        }
        
        $folha = new FolhaPagamento($publicacao, $situacaoAberta, $command->getNuMes(), $command->getNuAno());
        
        $tramitacaoSituacaoFolha = new TramitacaoSituacaoFolha($folha, $situacaoAberta, $command->getPessoaPerfilAtor());
        $folha->addTramitacaoSituacao($tramitacaoSituacaoFolha);
        
        $this->folhaPagamentoRepository->add($folha);
    }
}