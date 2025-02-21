<?php

namespace App\CommandBus;

use App\Entity\Programa;
use App\Entity\Publicacao;
use App\Entity\QuantidadePublicacao;
use App\Entity\TipoQuantitativoPublicacao;
use App\Repository\ProgramaRepository;
use App\Repository\TipoQuantitativoPublicacaoRepository;

class CadastrarProgramaHandler
{
    /**
     * @var ProgramaRepository
     */
    protected $programaRepository;
    
    /**
     * @var TipoQuantitativoPublicacaoRepository
     */
    protected $tipoQuantitativoPublicacaoRepository;

    /**
     * @param ProgramaRepository $programaRepository
     * @param TipoQuantitativoPublicacaoRepository $tipoQuantitativoPublicacaoRepository
     */
    public function __construct($programaRepository, $tipoQuantitativoPublicacaoRepository)
    {
        $this->programaRepository = $programaRepository;
        $this->tipoQuantitativoPublicacaoRepository = $tipoQuantitativoPublicacaoRepository;
    }

    /**
     * @param CadastrarProgramaCommand $command
     */
    public function handle(CadastrarProgramaCommand $command)
    {
        $programa = new Programa(
            $command->getDsPrograma(),
            $command->getTpAreaTematica(),
            $command->getTpPrograma()
        );

        $publicacao = new Publicacao(
            $programa,
            $command->getNuPublicacao(),
            $command->getDtPublicacao(),
            $command->getDtInicioVigencia(),
            $command->getDtFimVigencia(),
            $command->getTpPublicacao(),
            $command->getDsPublicacao()
        );

        $this
            ->addNovaQuantidade(
                $publicacao, TipoQuantitativoPublicacao::QTD_MINIMA_BOLSISTAS_GRUPO, $command->getQtdMinimaBolsistasGrupo()
            )
            ->addNovaQuantidade(
                $publicacao, TipoQuantitativoPublicacao::QTD_MAXIMA_BOLSISTAS_GRUPO, $command->getQtdMaximaBolsistasGrupo()
            )
        ;

        $programa->addPublicacao($publicacao);

        $this->programaRepository->add($programa);
    }
    
    /**
     * @param Publicacao $publicacao
     * @param integer $tipoQuantitativoId
     * @return CadastrarProgramaHandler
     */
    private function addNovaQuantidade($publicacao, $tipoQuantitativoId, $quantidade)
    {
        $publicacao->addQuantidade(
            new QuantidadePublicacao(
                $publicacao,
                $this->tipoQuantitativoPublicacaoRepository->find($tipoQuantitativoId),
                $quantidade
            )
        );
        
        return $this;
    }
}