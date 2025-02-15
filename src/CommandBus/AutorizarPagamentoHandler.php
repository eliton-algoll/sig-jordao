<?php

namespace App\CommandBus;

use App\CommandBus\AutorizarPagamentoCommand;
use App\Entity\AutorizacaoFolha;
use App\Entity\ProjetoFolhaPagamento;
use App\Repository\AutorizacaoFolhaRepository;
use App\Repository\ProjetoFolhaPagamentoRepository;
use App\Repository\ProjetoPessoaRepository;
use App\Repository\SituacaoProjetoFolhaRepository;

class AutorizarPagamentoHandler
{        
    /**
     *
     * @var ProjetoPessoaRepository
     */
    private $projetoPessoaRepository;
    
    /**
     *
     * @var ProjetoFolhaPagamentoRepository
     */
    private $projetoFolhaPagamentoRepository;
    
    /**
     *
     * @var AutorizacaoFolhaRepository
     */
    private $autorizacaoFolhaRepository;
    
    /**
     *
     * @var SituacaoProjetoFolhaRepository
     */
    private $situacaoProjetoFolhaRepository;
    
    /**
     * @param ProjetoPessoaRepository $projetoPessoaRepository
     * @param ProjetoFolhaPagamentoRepository $projetoFolhaPagamentoRepository
     * @param AutorizacaoFolhaRepository $autorizacaoFolhaRepository
     * @param SituacaoProjetoFolhaRepository $situacaoProjetoFolhaRepository
     */
    public function __construct(
        ProjetoPessoaRepository $projetoPessoaRepository,
        ProjetoFolhaPagamentoRepository $projetoFolhaPagamentoRepository,
        AutorizacaoFolhaRepository $autorizacaoFolhaRepository,
        SituacaoProjetoFolhaRepository $situacaoProjetoFolhaRepository
    ) {        
        $this->projetoPessoaRepository          = $projetoPessoaRepository;
        $this->projetoFolhaPagamentoRepository  = $projetoFolhaPagamentoRepository;
        $this->autorizacaoFolhaRepository       = $autorizacaoFolhaRepository;
        $this->situacaoProjetoFolhaRepository   = $situacaoProjetoFolhaRepository;
    }
    
    /**
     * @param AutorizarPagamentoCommand $command
     */
    public function handle(AutorizarPagamentoCommand $command)
    {
        $this->validateUniqueParticipante($command);
        
        $projetoFolha = new ProjetoFolhaPagamento(
            $command->getProjeto(),
            $command->getFolhaPagamento(),
            $this->situacaoProjetoFolhaRepository->getSituacaoAutorizada(),
            $command->getPessoaPerfil(),
            'S',
            $command->getJustificativa()
        );                
        
        if ($command->getCoordenadorDeProjetoNaoVoluntario()) {
            $projetoPessoa = $this->projetoPessoaRepository->find($command->getCoordenadorDeProjetoNaoVoluntario());

            if ($projetoPessoa) {
                $dadoPessoal = $projetoPessoa->getPessoaPerfil()->getPessoaFisica()->getDadoPessoal();
                new AutorizacaoFolha($projetoFolha, $projetoPessoa, $projetoPessoa->getVlBolsa(), 'S', $dadoPessoal->getBanco()->getCoBanco(), $dadoPessoal->getAgencia(), $dadoPessoal->getConta());
            }
        }

        if ($command->getOrientadorDeProjetoNaoVoluntario()) {
            $projetoPessoa = $this->projetoPessoaRepository->find($command->getOrientadorDeProjetoNaoVoluntario());

            if ($projetoPessoa) {
                $dadoPessoal = $projetoPessoa->getPessoaPerfil()->getPessoaFisica()->getDadoPessoal();
                new AutorizacaoFolha($projetoFolha, $projetoPessoa, $projetoPessoa->getVlBolsa(), 'S', $dadoPessoal->getBanco()->getCoBanco(), $dadoPessoal->getAgencia(), $dadoPessoal->getConta());
            }
        }
        
        foreach ($command->getParticipantes() as $grupo) {           
            foreach ($grupo as $participante) {
                $projetoPessoa = $this->projetoPessoaRepository->find($participante);

                if ($projetoPessoa) {
                    $dadoPessoal = $projetoPessoa->getPessoaPerfil()->getPessoaFisica()->getDadoPessoal();
                    new AutorizacaoFolha($projetoFolha, $projetoPessoa, $projetoPessoa->getVlBolsa(), 'S', $dadoPessoal->getBanco()->getCoBanco(), $dadoPessoal->getAgencia(), $dadoPessoal->getConta());
                }
            }
        }        

        $this->projetoFolhaPagamentoRepository->add($projetoFolha);
    }

    /**
     * 
     * @param AutorizarPagamentoCommand $command
     * @throws \Exception   
     */
    public function validateUniqueParticipante(AutorizarPagamentoCommand $command)
    {
        $cpfParticipantes = array();
        $erros = array(); 
       
        foreach ($command->getParticipantes() as $grupo) {
            foreach ($grupo as $participante) {
                $projetoPessoa = $this->projetoPessoaRepository->find($participante);
                if ($projetoPessoa) {
                    $cpf = $projetoPessoa->getPessoaPerfil()->getPessoaFisica()->getNuCpf();
                    if (!in_array($cpf, $cpfParticipantes)) {
                        $cpfParticipantes[] = $cpf;
                    } else {
                        $erros[] = 'O participante '. $projetoPessoa->getPessoaPerfil()->getPessoaFisica()->getPessoa()->getNoPessoa() .' não pode receber mais de um vez no mesmo período';
                    }
                }
            }
        }

        if ($erros) {
            throw new \UnexpectedValueException(implode('</br>', $erros));
        }
    }
}
