<?php

namespace AppBundle\CommandBus;

use AppBundle\Validator\Constraints as SigpetConstraints;
use AppBundle\Entity\FolhaPagamento;
use AppBundle\Entity\PessoaPerfil;
use AppBundle\Entity\Projeto;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class AutorizarPagamentoCommand
{    
    /**
     *
     * @var PessoaPerfil
     */
    private $pessoaPerfil;
    
    /**
     *
     * @var array          
     */
    private $participantes = array();
    
    /**
     *
     * @var integer
     */
    private $coordenadorDeProjetoNaoVoluntario;

    /**
     *
     * @var integer
     */
    private $orientadorDeProjetoNaoVoluntario;
    
    /**
     *
     * @var string
     * 
     * @Assert\NotBlank( message = "É obrigatório o preenchimento do campo Relatório Mensal de Atividades" )
     * @Assert\Length(
     *      max = 4000,
     *      maxMessage = "A justificativa não pode ter mais de {{ limit }} caracteres."
     * )
     */
    private $justificativa;
    
    /**
     *
     * @var Projeto 
     */
    private $projeto;
    
    /**
     *
     * @var FolhaPagamento
     */
    private $folhaPagamento;
    
    /**
     *
     * @var string
     * 
     * @Assert\NotBlank( message = "É obrigatório o preenchimento do campo Declaração" )
     * @Assert\EqualTo(
     *      value = "S",
     *      message = "É obrigatório o preenchimento do campo Declaração"
     * )
     */
    private $stDeclaracao;
    
    /**
     * 
     * @return PessoaPerfil
     */
    public function getPessoaPerfil()
    {
        return $this->pessoaPerfil;
    }
            
    /**
     * 
     * @return null|array
     */
    public function getParticipantes()
    {
        return $this->participantes;
    }
    
    /**
     * 
     * @return integer
     */
    public function getCoordenadorDeProjetoNaoVoluntario()
    {
        return $this->coordenadorDeProjetoNaoVoluntario;
    }

    /**
     *
     * @return integer
     */
    public function getOrientadorDeProjetoNaoVoluntario()
    {
        return $this->orientadorDeProjetoNaoVoluntario;
    }
    
    /**
     * 
     * @return string
     */
    public function getJustificativa()
    {
        return $this->justificativa;
    }

    /**
     * 
     * @return Projeto
     */
    public function getProjeto()
    {
        return $this->projeto;
    }

    /**
     * 
     * @return FolhaPagamento
     */
    public function getFolhaPagamento()
    {
        return $this->folhaPagamento;
    }
    
    /**
     * 
     * @return string
     */
    public function getStDeclaracao()
    {
        return $this->stDeclaracao;
    }
    
    /**
     * 
     * @param PessoaPerfil $pessoaPerfil
     */
    public function setPessoaPerfil(PessoaPerfil $pessoaPerfil)
    {
        $this->pessoaPerfil = $pessoaPerfil;
    }
                
    /**
     * 
     * @param null|array $participantes
     */
    public function setParticipantes($participantes)
    {
        $this->participantes = $participantes;
    }
    
    /**
     * 
     * @param string $coordenadorDeProjetoNaoVoluntario
     */
    public function setCoordenadorDeProjetoNaoVoluntario($coordenadorDeProjetoNaoVoluntario)
    {
        $this->coordenadorDeProjetoNaoVoluntario = $coordenadorDeProjetoNaoVoluntario;
    }

    /**
     *
     * @param string $orientadorDeProjetoNaoVoluntario
     */
    public function setOrientadorDeProjetoNaoVoluntario($orientadorDeProjetoNaoVoluntario)
    {
        $this->orientadorDeProjetoNaoVoluntario = $orientadorDeProjetoNaoVoluntario;
    }
    
    /**
     * 
     * @param string $justificativa
     */
    public function setJustificativa($justificativa)
    {
        $this->justificativa = $justificativa;
    }

    /**
     * 
     * @param Projeto $projeto
     */
    public function setProjeto(Projeto $projeto)
    {
        $this->projeto = $projeto;
    }

    /**
     * 
     * @param FolhaPagamento $folhaPagamento
     */
    public function setFolhaPagamento(FolhaPagamento $folhaPagamento)
    {
        $this->folhaPagamento = $folhaPagamento;
    }    
    
    /**
     * 
     * @param string $stDeclaracao
     */
    public function setStDeclaracao($stDeclaracao)
    {
        $this->stDeclaracao = $stDeclaracao;
    }
        
    /**
     * @Assert\Callback
     * 
     * @param ExecutionContextInterface $context
     */
    public function checkHasParticipanteSelecionado(ExecutionContextInterface $context)
    {
        if (empty($this->participantes) && $this->coordenadorDeProjetoNaoVoluntario == '') {
            $context->buildViolation('Selecione pelo menos um participante')                
                ->addViolation();
        }
    }
    
    /**
     * @Assert\Callback
     * 
     * @param ExecutionContextInterface $context
     */
    public function validateGrupos(ExecutionContextInterface $context)
    {
        $publicacao = $this->getProjeto()->getPublicacao();

        if ($publicacao->getPrograma()->isGrupoTutorial()) {
            return;
        }
        
        $countErrosMaxGrupo = $countErrosMinGrupo = $totalBolsistas = 0;
        $qtMaxGrupo         = $publicacao->getQuantidadeMaximaGrupo()->getQtParticipante();
        $qtMinGrupo         = $publicacao->getQuantidadeMinimaGrupo()->getQtParticipante();
        $qtMaxBolsista      = $this->getProjeto()->getQtBolsa();                
        
        foreach ($this->participantes as $grupo) {
            if (count($grupo) > $qtMaxGrupo) {
                $countErrosMaxGrupo++;
            }
            if (count($grupo) < $qtMinGrupo) {
                $countErrosMinGrupo++;
            }
            $totalBolsistas += count($grupo);
        }        
        
        if (10 > strlen($this->justificativa)) {
            $context->buildViolation('O relatório mensal de atividades deve possuir pelo menos 10 caracteres.')
                ->addViolation();
        }
        
        if ($countErrosMaxGrupo > 0) {
            $context->buildViolation('Tem ' . $countErrosMaxGrupo . ' grupo(s) com quantidade superior à permitida.')
                ->addViolation();
        }
        
        if ($totalBolsistas > $qtMaxBolsista) {
            $context->buildViolation('A quantidade de bolsistas não pode ultrapassar o quantidade máxima.')
                ->addViolation();
        }
    }
}
