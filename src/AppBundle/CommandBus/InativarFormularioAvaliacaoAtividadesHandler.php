<?php

namespace AppBundle\CommandBus;

use AppBundle\CommandBus\InativarFormularioAvaliacaoAtividadesCommand;
use AppBundle\Repository\FormularioAvaliacaoAtividadeRepository;

final class InativarFormularioAvaliacaoAtividadesHandler
{
    /**
     *
     * @var FormularioAvaliacaoAtividadeRepository 
     */
    private $formularioAvaliacaoAtividadeRepository;
    
    /**
     * 
     * @param FormularioAvaliacaoAtividadeRepository $formularioAvaliacaoAtividadeRepository
     */
    public function __construct(FormularioAvaliacaoAtividadeRepository $formularioAvaliacaoAtividadeRepository)
    {
        $this->formularioAvaliacaoAtividadeRepository = $formularioAvaliacaoAtividadeRepository;
    }

    /**
     * 
     * @param InativarFormularioAvaliacaoAtividadesCommand $command
     */
    public function handle(InativarFormularioAvaliacaoAtividadesCommand $command)
    {
        $formularioAvaliacaoAtividade = $command->getFormularioAvaliacaoAtividade();
        
        /**
         * @todo verificar se o formulário está vinculado algum tramite
         */
        
        if (!$command->isSoftDelete()) {            
            $this->formularioAvaliacaoAtividadeRepository->remove($formularioAvaliacaoAtividade);
        } else {
            $formularioAvaliacaoAtividade->inativar();
            $this->formularioAvaliacaoAtividadeRepository->add($formularioAvaliacaoAtividade);
        }
    }
}
