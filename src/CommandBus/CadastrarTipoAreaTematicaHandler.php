<?php

namespace App\CommandBus;

use App\Entity\CursoGraduacao;
use App\Entity\TipoAreaTematica;
use App\Repository\CursoGraduacaoRepository;
use App\Repository\TipoAreaTematicaRepository;

class CadastrarTipoAreaTematicaHandler
{

    /**
     * @var TipoAreaTematicaRepository
     */
    private $tipoAreaTematicaRepository;

    /**
     * @var CursoGraduacaoRepository
     */
    private $cursoGraduacaoRepository;

    /**
     * @param TipoAreaTematicaRepository $tipoAreaTematicaRepository
     */
    public function __construct(
        TipoAreaTematicaRepository $tipoAreaTematicaRepository,
        CursoGraduacaoRepository $cursoGraduacaoRepository
    ) {
        $this->tipoAreaTematicaRepository = $tipoAreaTematicaRepository;
        $this->cursoGraduacaoRepository = $cursoGraduacaoRepository;
    }
    
    /**
     * @param CadastrarTipoAreaTematicaCommand $command
     */
    public function handle(CadastrarTipoAreaTematicaCommand $command)
    {

        if( $command->getTipoAreaTematica() ) {
            $tipoAreaTematica = $command->getTipoAreaTematica();
            $cursoGraducao = $this->cursoGraduacaoRepository->findBy(['dsCursoGraduacao' => $tipoAreaTematica->getDsTipoAreaTematica(),
                                                                      'stRegistroAtivo' => $tipoAreaTematica->getStRegistroAtivo() ]);

            $tipoAreaTematica->setDsTipoAreaTematica($command->getDsTipoAreaTematica());
            $tipoAreaTematica->setTpAreaFormacao($command->getTpAreaFormacao());
            $tipoAreaTematica->setTpAreaTematica($command->getTpAreaTematica());
            if( isset($cursoGraducao[0]) ) {
                $cursoGraducao = $cursoGraducao[0];
                $cursoGraducao->setDsCursoGraduacao($command->getDsTipoAreaTematica());
            }
        } else {
            $tipoAreaTematica = new TipoAreaTematica($command->getDsTipoAreaTematica(),
                                                     $command->getTpAreaTematica(),
                                                     $command->getTpAreaFormacao());
            $cursoGraducao = new CursoGraduacao($command->getDsTipoAreaTematica());
        }

        $this->tipoAreaTematicaRepository->add($tipoAreaTematica);
        if( $cursoGraducao ) {
            $this->cursoGraduacaoRepository->add($cursoGraducao);
        }
    }
}
