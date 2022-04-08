<?php

namespace AppBundle\CommandBus;

use AppBundle\Entity\AutorizacaoFolha;
use AppBundle\Entity\ProjetoFolhaPagamento;
use AppBundle\Repository\ProjetoPessoaRepository;
use AppBundle\Repository\SituacaoProjetoFolhaRepository;
use AppBundle\Repository\SituacaoFolhaRepository;
use AppBundle\Repository\FolhaPagamentoRepository;
use AppBundle\Exception\FolhaSuplementarException;
use AppBundle\Entity\SituacaoProjetoFolha;
use AppBundle\Entity\SituacaoFolha;
use AppBundle\Entity\FolhaPagamento;

final class CadastarFolhaSuplementarHandler
{
    /**
     *
     * @var FolhaPagamentoRepository 
     */
    private $folhaPagamentoRepository;
    
    /**
     *
     * @var SituacaoFolhaRepository 
     */
    private $situacaoFolhaRepository;
    
    /**
     *
     * @var ProjetoPessoaRepository
     */
    private $projetoPessoaRepository;
    
    /**
     *
     * @var SituacaoProjetoFolhaRepository 
     */
    private $situacaoProjetoFolhaRepository;    
    
    /**
     * 
     * @param FolhaPagamentoRepository $folhaPagamentoRepository
     * @param SituacaoFolhaRepository $situacaoFolhaRepository
     * @param ProjetoPessoaRepository $projetoPessoaRepository
     * @param SituacaoProjetoFolhaRepository $situacaoProjetoFolhaRepository
     */
    public function __construct(
        FolhaPagamentoRepository $folhaPagamentoRepository,
        SituacaoFolhaRepository $situacaoFolhaRepository,
        ProjetoPessoaRepository $projetoPessoaRepository,
        SituacaoProjetoFolhaRepository $situacaoProjetoFolhaRepository        
    ) {
        $this->folhaPagamentoRepository = $folhaPagamentoRepository;
        $this->situacaoFolhaRepository = $situacaoFolhaRepository;
        $this->projetoPessoaRepository = $projetoPessoaRepository;
        $this->situacaoProjetoFolhaRepository = $situacaoProjetoFolhaRepository;        
    }

    /**
     * @param CadastarFolhaSuplementarCommand $command
     * @throws FolhaSuplementarException
     */
    public function handle(CadastarFolhaSuplementarCommand $command)
    {
        if ($command->fechaFolha()) {
            $situacaoFolha = $this->situacaoFolhaRepository->find(SituacaoFolha::FECHADA);
        } else {
            $situacaoFolha = $this->situacaoFolhaRepository->find(SituacaoFolha::ABERTA);
        }
        $situacaoProjetoFolha = $this->situacaoProjetoFolhaRepository->find(SituacaoProjetoFolha::AUTORIZADA);
        
        if (0 === count($command->getParticipantesAsArray())) {
            throw FolhaSuplementarException::emptyFolha();
        }
        
        if ($command->getFolhaPagamentoSuplementar()) {
            $folhaPagamento = $command->getFolhaPagamentoSuplementar();
            $folhaPagamento->setSituacao($situacaoFolha);
        } else {
            $folhaPagamento = new FolhaPagamento(
                $command->getPublicacao(),
                $situacaoFolha,
                $command->getFolhaPagamento()->getNuMes(),
                $command->getFolhaPagamento()->getNuAno(),
                null,
                null,
                null,
                $command->getDsJustificativa()
            );
        }
        
        $folhaPagamento->setTpFolhaSuplementar();

        foreach ($command->getParticipantesAsArray() as $participante) {

            $projetoPessoa = $this->projetoPessoaRepository->find($participante);

            if (!$projetoPessoa) continue;

            $projeto = $projetoPessoa->getProjeto();

            if ($projeto->getPublicacao() !== $folhaPagamento->getPublicacao()) {
                throw FolhaSuplementarException::onParticipanteBelongsToAnotherPublicacao($projetoPessoa);
            }
            if ($projetoPessoa->isVoluntario()) {
                throw FolhaSuplementarException::onParticipanteIsVoluntario($projetoPessoa);
            }

            if (!$folhaPagamento->hasProjetoFolhaPagamento($projeto)) {
                $projetoFolhaPagamento = new ProjetoFolhaPagamento(
                    $projeto,
                    $folhaPagamento,
                    $situacaoProjetoFolha,
                    $command->getPessoaPerfil(),
                    'S'
                );
            } else {
                $projetoFolhaPagamento = $folhaPagamento->getProjetoFolhaPagamentoByProjeto($projeto);
            }

            $dadoPessoal = $projetoPessoa->getPessoaPerfil()->getPessoaFisica()->getDadoPessoal();

            if ($autorizacaoFolha = $projetoFolhaPagamento->getAutorizacaoByProjetoPessoa($projetoPessoa)) {
                $autorizacaoFolha->ativar();
            } else {
                new AutorizacaoFolha(
                    $projetoFolhaPagamento,
                    $projetoPessoa,
                    $projetoPessoa->getVlBolsa(),
                    'S',
                    $dadoPessoal->getBanco()->getCoBanco(),
                    $dadoPessoal->getAgencia(),
                    $dadoPessoal->getConta()
                );
            }

            $folhaPagamento->addRawProjetoFolhaPagamento($projetoFolhaPagamento);
        }

        $this->folhaPagamentoRepository->add($folhaPagamento);
    }
}
