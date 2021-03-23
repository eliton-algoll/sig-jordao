<?php

namespace AppBundle\CommandBus;

use AppBundle\CommandBus\InativarEstabelecimentoCommand;
use AppBundle\Repository\ProjetoEstabelecimentoRepository;

class InativarEstabelecimentoHandler
{
    /**
     * @var ProjetoEstabelecimentoRepository
     */
    private $projetoEstabelecimentoRepository;
    
    /**
     * @param ProjetoEstabelecimentoRepository $projetoEstabelecimentoRepository
     */
    public function __construct(ProjetoEstabelecimentoRepository $projetoEstabelecimentoRepository)
    {
        $this->projetoEstabelecimentoRepository = $projetoEstabelecimentoRepository;
    }

    /**
     * @param InativarEstabelecimentoCommand $command
     */
    public function handle(InativarEstabelecimentoCommand $command)
    {
        $estabelecimento = $this->projetoEstabelecimentoRepository->find($command->getCoSeqProjetoEstabelec());
        $estabelecimento->inativar();
        
        $this->projetoEstabelecimentoRepository->add($estabelecimento);
    }
}