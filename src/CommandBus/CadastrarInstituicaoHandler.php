<?php

namespace App\CommandBus;


use App\Entity\Instituicao;
use App\Repository\InstituicaoRepository;
use App\Repository\MunicipioRepository;
use App\Repository\ProjetoRepository;
use App\Repository\PessoaJuridicaRepository;
use App\CommandBus\CadastrarInstituicaoCommand;

class CadastrarInstituicaoHandler
{
    /**
     * @var PessoaJuridicaRepository
     */
    private $pessoaJuridicaRepository;

    /**
     * @var MunicipioRepository
     */
    private $municipioRepository;
    /**
     * @var InstituicaoRepository
     */
    private $instituicaoRepository;

    /**
     * @param PessoaJuridicaRepository $pessoaJuridicaRepository
     * @param MunicipioRepository $municipioRepository
     * @param InstituicaoRepository $instituicaoRepository
     */
    public function __construct(
        PessoaJuridicaRepository $pessoaJuridicaRepository,
        MunicipioRepository      $municipioRepository,
        InstituicaoRepository $instituicaoRepository
    ) {
        $this->pessoaJuridicaRepository = $pessoaJuridicaRepository;
        $this->municipioRepository = $municipioRepository;
        $this->instituicaoRepository = $instituicaoRepository;
    }
    
    /**
     * @param CadastrarInstituicaoCommand $command
     */
    public function handle(CadastrarInstituicaoCommand $command)
    {

        $cnpj = preg_replace("/[^0-9]/", "",$command->getNuCnpj());
        $pessoaJuridica = $this->pessoaJuridicaRepository->findOneBy(array(
            'nuCnpj' => $cnpj,
            'stRegistroAtivo' => 'S'
        ));

        if( !$pessoaJuridica ) {
            throw new \InvalidArgumentException('CNPJ não localizado.');
        }

        $municipio = $this->municipioRepository->findOneBy(array(
            'coMunicipioIbge' => $command->getMunicipio(),
            'stRegistroAtivo' => 'S'
        ));

        if( !$municipio ) {
            throw new \InvalidArgumentException('Município não localizado.');
        }


        if( $command->getInstituicao() ) {
            $instituicao = $command->getInstituicao();
            $instituicao->setNoInstituicaoProjeto($command->getNoInstituicaoProjeto());
            $instituicao->setPessoaJuridica($pessoaJuridica);
            $instituicao->setMunicipio($municipio);
        } else {
            $instituicao = new Instituicao($pessoaJuridica, $municipio, $command->getNoInstituicaoProjeto());
        }

        $this->instituicaoRepository->add($instituicao);
    }
}
