<?php

namespace AppBundle\CommandBus;

use AppBundle\CommandBus\SalvarEmissaoCertificadoCommand;
use AppBundle\Entity\EmissaoCertificado;
use AppBundle\Repository\EmissaoCertificadoRepository;
use AppBundle\Repository\ProjetoPessoaRepository;

final class SalvarEmissaoCertificadoHandler
{
    /**
     *
     * @var EmissaoCertificadoRepository 
     */
    private $emissaoCertificadoRepository;
    
    /**
     *
     * @var ProjetoPessoaRepository 
     */
    private $projetoPessoaRepository;
    
    /**
     * 
     * @param EmissaoCertificadoRepository $emissaoCertificadoRepository
     * @param ProjetoPessoaRepository $projetoPessoaRepository
     */
    public function __construct(
        EmissaoCertificadoRepository $emissaoCertificadoRepository,
        ProjetoPessoaRepository $projetoPessoaRepository
    ) {
        $this->emissaoCertificadoRepository = $emissaoCertificadoRepository;
        $this->projetoPessoaRepository = $projetoPessoaRepository;
    }
    
    /**
     * 
     * @param SalvarEmissaoCertificadoCommand $command
     */
    public function handle(SalvarEmissaoCertificadoCommand $command)
    {
        foreach ($command->getParticipantes() as $id) {
            
            $projetoPessoaParticipante = $this->projetoPessoaRepository->find($id);
            
            $emissaoCertificado = new EmissaoCertificado(
                $projetoPessoaParticipante,
                $command->getProjetoPessoaResponsavel(),
                $command->getMunicipio(),
                $command->getQtCargaHoraria()
            );
            
            $this->emissaoCertificadoRepository->add($emissaoCertificado);
            
            $command->addProjetoPessoaParticipantes($projetoPessoaParticipante);
        }
    }
}
