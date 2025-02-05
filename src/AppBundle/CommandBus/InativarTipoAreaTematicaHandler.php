<?php

namespace AppBundle\CommandBus;

use AppBundle\CommandBus\InativarUsuarioCommand;
use AppBundle\Controller\TipoAreaTematicaController;
use AppBundle\Repository\CursoGraduacaoRepository;
use AppBundle\Repository\TipoAreaTematicaRepository;
use AppBundle\Repository\UsuarioRepository;
use Symfony\Component\Validator\Constraints as Assert;

final class InativarTipoAreaTematicaHandler
{
    /**
     *
     * @var TipoAreaTematicaRepository
     */
    private $tipoAreaTematicaRepository;

    /**
     * @var CursoGraduacaoRepository
     */
    private $cursoGraduacaoRepository;
    
    /**
     * 
     * @param TipoAreaTematicaRepository $tipoAreaTematicaRepository
     */
    public function __construct(
        TipoAreaTematicaRepository $tipoAreaTematicaRepository,
        CursoGraduacaoRepository  $cursoGraduacaoRepository
    ) {
        $this->tipoAreaTematicaRepository = $tipoAreaTematicaRepository;
        $this->cursoGraduacaoRepository   = $cursoGraduacaoRepository;
    }
    
    /**
     * 
     * @param InativarTipoAreaTematicaCommand $command
     */
    public function handle(InativarTipoAreaTematicaCommand $command)
    {
        $tpAreaTematica = $command->getTipoAreaTematica();
        $cursoGraducao = $this->cursoGraduacaoRepository->findBy(['dsCursoGraduacao' => $tpAreaTematica->getDsTipoAreaTematica(),
                                                                  'stRegistroAtivo' => $tpAreaTematica->getStRegistroAtivo() ]);
        $stRegistroAtivo = ($tpAreaTematica->getStRegistroAtivo() === 'S') ? 'N' : 'S';
        $tpAreaTematica->setStRegistroAtivo($stRegistroAtivo);

        if( isset($cursoGraducao[0]) ) {
            $cursoGraducao = $cursoGraducao[0];
            $cursoGraducao->setStRegistroAtivo($stRegistroAtivo);
            $this->cursoGraduacaoRepository->add($cursoGraducao);
        }

        $this->tipoAreaTematicaRepository->add($tpAreaTematica);
    }

}
