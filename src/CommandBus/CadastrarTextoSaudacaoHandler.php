<?php

namespace App\CommandBus;


use App\Entity\Instituicao;
use App\Repository\InstituicaoRepository;
use App\Repository\MunicipioRepository;
use App\Repository\ProjetoRepository;
use App\Repository\PessoaJuridicaRepository;
use App\CommandBus\CadastrarInstituicaoCommand;
use App\Repository\TextoSaudacaoRepository;

class CadastrarTextoSaudacaoHandler
{
    /**
     * @var TextoSaudacaoRepository
     */
    private $textoSaudacaoRepository;

    /**
     * @param TextoSaudacaoRepository $textoSaudacaoRepository
     */
    public function __construct(
        TextoSaudacaoRepository $textoSaudacaoRepository
    ) {
        $this->textoSaudacaoRepository = $textoSaudacaoRepository;
    }
    
    /**
     * @param CadastrarTextoSaudacaoCommand $command
     */
    public function handle(CadastrarTextoSaudacaoCommand $command)
    {
        $textoSaudacao = $command->getTextoSaudacao();
        $textoSaudacao->setDsTextoSaudacao($command->getDsTextoSaudacao());

        $this->textoSaudacaoRepository->add($textoSaudacao);
    }
}
