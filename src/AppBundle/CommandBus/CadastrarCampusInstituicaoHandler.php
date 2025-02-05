<?php

namespace AppBundle\CommandBus;


use AppBundle\Entity\CampusInstituicao;
use AppBundle\Entity\Instituicao;
use AppBundle\Repository\CampusInstituicaoRepository;
use AppBundle\Repository\InstituicaoRepository;
use AppBundle\Repository\MunicipioRepository;
use AppBundle\Repository\ProjetoRepository;
use AppBundle\Repository\PessoaJuridicaRepository;
use AppBundle\CommandBus\CadastrarInstituicaoCommand;

class CadastrarCampusInstituicaoHandler
{
    /**
     * @var InstituicaoRepository
     */
    private $instituicaoRepository;

    /**
     * @var MunicipioRepository
     */
    private $municipioRepository;

    /**
     * @var CampusInstituicaoRepository
     */
    private $campusInstituicaoRepository;

    /**
     * @param InstituicaoRepository $instituicaoRepository
     * @param MunicipioRepository $municipioRepository
     */
    public function __construct(
        MunicipioRepository   $municipioRepository,
        InstituicaoRepository $instituicaoRepository
    ) {
        $this->municipioRepository = $municipioRepository;
        $this->instituicaoRepository = $instituicaoRepository;
    }
    
    /**
     * @param CadastrarCampusInstituicaoCommand $command
     */
    public function handle(CadastrarCampusInstituicaoCommand $command)
    {
        $municipio = $this->municipioRepository->findOneBy(array(
            'coMunicipioIbge' => $command->getMunicipio(),
            'stRegistroAtivo' => 'S'
        ));

        if( !$municipio ) {
            throw new \InvalidArgumentException('Município não localizado.');
        }

        $instituicao = $this->instituicaoRepository->findOneBy(array(
            'coSeqInstituicao' => $command->getInstituicao(),
            'stRegistroAtivo' => 'S'
        ));

        if( !$instituicao ) {
            throw new \InvalidArgumentException('Instituição não localizada.');
        }


        if( $command->getCampusInstituicao() ) {
            $campusInstituicao = $command->getCampusInstituicao();
            $campusInstituicao->setInstituicao($instituicao);
            $campusInstituicao->setMunicipio($municipio);
            $campusInstituicao->setNoCampus($command->getNoCampus());
        } else {
            $campusInstituicao = new CampusInstituicao($instituicao, $command->getNoCampus(), $municipio);
        }

        $this->instituicaoRepository->add($campusInstituicao);
    }
}
