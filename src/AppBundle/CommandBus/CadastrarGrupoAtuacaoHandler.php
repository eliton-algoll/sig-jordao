<?php

namespace AppBundle\CommandBus;

use AppBundle\CommandBus\CadastrarGrupoAtuacaoCommand;
use AppBundle\Entity\GrupoAtuacao;
use AppBundle\Repository\GrupoAtuacaoRepository;

class CadastrarGrupoAtuacaoHandler
{
    /**
     * @var GrupoAtuacaoRepository
     */
    protected $grupoAtuacaoRepository;
    
    /**
     * @param GrupoAtuacaoRepository $grupoAtuacaoRepository
     */
    public function __construct(GrupoAtuacaoRepository $grupoAtuacaoRepository)
    {
        $this->grupoAtuacaoRepository = $grupoAtuacaoRepository;
    }

    /**
     * @param CadastrarGrupoAtuacaoCommand $command
     */
    public function handle(CadastrarGrupoAtuacaoCommand $command)
    {
        $grupo = new GrupoAtuacao();
        
        if (is_array($command->getAreasTematicas())) {
            
            foreach ($command->getAreasTematicas() as $tipoAreaTematica) {
                $grupo->addAreaTematica($tipoAreaTematica);
            }
            
        } else {
            $grupo->addAreaTematica($command->getAreasTematicas());
        }
        
        $this->grupoAtuacaoRepository->add($grupo);
    }
}