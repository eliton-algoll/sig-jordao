<?php

namespace AppBundle\CommandBus;

use AppBundle\CommandBus\EnviarFolhaPagamentoParaFundoCommand;
use AppBundle\Repository\FolhaPagamentoRepository;
use AppBundle\Repository\SituacaoFolhaRepository;
use AppBundle\Repository\ProjetoFolhaPagamentoRepository;
use AppBundle\Repository\SituacaoProjetoFolhaRepository;
use AppBundle\Repository\IntegracaoRepository;
use AppBundle\Entity\SituacaoFolha;
use AppBundle\Entity\SituacaoProjetoFolha;
use AppBundle\Entity\Integracao;
use AppBundle\Entity\TramitacaoSituacaoFolha;

class EnviarFolhaPagamentoParaFundoHandler
{
    /**
     * @var FolhaPagamentoRepository
     */
    private $folhaPagamentoRepository;
    
    /**
     * @var SituacaoFolhaRepository
     */
    private $situacaoFolhaRepository;
    
    /**
     * @var ProjetoFolhaPagamentoRepository
     */
    private $projetoFolhaPagamentoRepository;
    
    /**
     * @var SituacaoProjetoFolhaRepository
     */
    private $situacaoProjetoFolhaRepository;
    
    /**
     * @var IntegracaoRepository
     */
    private $integracaoRepository;
    
    /**
     * @param FolhaPagamentoRepository $folhaPagamentoRepository
     * @param SituacaoFolhaRepository $situacaoFolhaRepository
     * @param ProjetoFolhaPagamentoRepository $ProjetoFolhaPagamentoRepository
     * @param SituacaoProjetoFolhaRepository $situacaoProjetoFolhaRepository
     * @param IntegracaoRepository
     */
    public function __construct(
        FolhaPagamentoRepository $folhaPagamentoRepository, 
        SituacaoFolhaRepository $situacaoFolhaRepository,
        ProjetoFolhaPagamentoRepository $projetoFolhaPagamentoRepository,
        SituacaoProjetoFolhaRepository $situacaoProjetoFolhaRepository,
        IntegracaoRepository $integracaoRepository
    ) {
        $this->folhaPagamentoRepository = $folhaPagamentoRepository;
        $this->situacaoFolhaRepository = $situacaoFolhaRepository;
        $this->projetoFolhaPagamentoRepository = $projetoFolhaPagamentoRepository;
        $this->situacaoProjetoFolhaRepository = $situacaoProjetoFolhaRepository;
        $this->integracaoRepository = $integracaoRepository;
    }

    /**
     * @param EnviarFolhaPagamentoParaFundoCommand $command
     */
    public function handle(EnviarFolhaPagamentoParaFundoCommand $command)
    {
        $situacaoEnviadaParaFundo = $this->situacaoFolhaRepository->find(SituacaoFolha::ENVIADA_FUNDO);
        
        $folha = $command->getFolhaPagamento();
        $folha->setSituacao($situacaoEnviadaParaFundo);
        $folha->setNuSipar($command->getNuSipar());
        
        $tramitacaoSituacaoFolha = new TramitacaoSituacaoFolha($folha, $situacaoEnviadaParaFundo, $command->getPessoaPerfilAtor());
        $folha->addTramitacaoSituacao($tramitacaoSituacaoFolha);
        
        $this->folhaPagamentoRepository->add($folha);
        
        $situacaoEnviadaParaPagamento = $this->situacaoProjetoFolhaRepository->find(SituacaoProjetoFolha::ENVIADA_PAGAMENTO);
        
        foreach($folha->getProjetosFolhaPagamentoHomologadas() as $folhaHomologada) {
            $folhaHomologada->setSituacao($situacaoEnviadaParaPagamento);
            
            foreach($folhaHomologada->getAutorizacoesAtivas() as $autorizacaoFolha) {
                $pessoaFisica = $autorizacaoFolha->getProjetoPessoa()->getPessoaPerfil()->getPessoaFisica();
               
                $integracao = new Integracao(
                    $folha->getNuSiparClean(),
                    $folha->getNuAno(),
                    $folha->getNuMes(),
                    $pessoaFisica->getNuCpf(),
                    $autorizacaoFolha->getVlBolsa(),
                    $autorizacaoFolha->getCoBanco(),
                    $autorizacaoFolha->getCoAgenciaBancaria(),
                    $autorizacaoFolha->getCoConta()
                );
                
                $this->integracaoRepository->add($integracao);
            }
            $this->projetoFolhaPagamentoRepository->add($folhaHomologada);
        }
    }
}