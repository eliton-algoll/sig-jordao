<?php

namespace App\CommandBus;


use App\Entity\CampusInstituicao;
use App\Entity\Instituicao;
use App\Repository\CampusInstituicaoRepository;
use App\Repository\InstituicaoRepository;
use App\Repository\MunicipioRepository;
use App\Repository\ProjetoRepository;
use App\Repository\PessoaJuridicaRepository;
use App\CommandBus\CadastrarInstituicaoCommand;

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
