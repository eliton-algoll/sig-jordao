<?php

namespace AppBundle\CommandBus;


use AppBundle\Entity\Instituicao;
use AppBundle\Repository\InstituicaoRepository;
use AppBundle\Repository\MunicipioRepository;
use AppBundle\Repository\ProjetoRepository;
use AppBundle\Repository\PessoaJuridicaRepository;
use AppBundle\CommandBus\CadastrarInstituicaoCommand;
use AppBundle\Repository\TextoSaudacaoRepository;

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
