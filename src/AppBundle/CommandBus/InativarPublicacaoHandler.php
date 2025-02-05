<?php

namespace AppBundle\CommandBus;

use AppBundle\Repository\PublicacaoRepository;

class InativarPublicacaoHandler
{
    /**
     * @var PublicacaoRepository
     */
    private $repository;
    
    /**
     * @param PublicacaoRepository $repository
     */
    public function __construct($repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param InativarPublicacaoCommand $command
     */
    public function handle(InativarPublicacaoCommand $command)
    {
        $publicacao = $this->repository->find($command->getCoSeqPublicacao());
        
        if ($publicacao->getPrograma()->getPublicacoesAtivas()->count() == 1) {
            throw new \DomainException('Não é possível deixar o projeto sem nenhuma publicação.');
        }
            
        $publicacao->inativar();
        
        $this->repository->add($publicacao);
    }
}