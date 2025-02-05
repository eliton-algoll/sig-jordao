<?php

namespace AppBundle\CommandBus;

use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Entity\Publicacao;
use AppBundle\Entity\PessoaPerfil;
use AppBundle\Entity\FolhaPagamento;

final class CadastarFolhaSuplementarCommand
{
    /**
     *@var FolhaPagamento
     */
    private $folhaPagamentoSuplementar;
    
    /**
     *@var PessoaPerfil
     */
    private $pessoaPerfil;
    
    /**
     *@var Publicacao
     */
    private $publicacao;
    
    /**
     *@var FolhaPagamento
     */
    private $folhaPagamento;
    
    /**
     *@var string
     *
     * @Assert\NotBlank( message = "Você deve selecionar pelo menos um participante." )
     */
    private $participantes;
    
    /**
     *@var string
     * 
     * @Assert\Length(
     *      max = 4000,
     *      maxMessage = "A justificativa deve conter no máximo {{ limit }} caracteres."
     * )
     */
    private $dsJustificativa;
    
    /**
     *@var string
     */
    private $salvaEfecha;
    
    /**
     * @return FolhaPagamento|null
     */
    public function getFolhaPagamentoSuplementar()
    {
        return $this->folhaPagamentoSuplementar;
    }
    
    /**
     * @return PessoaPerfil
     */
    public function getPessoaPerfil()
    {
        return $this->pessoaPerfil;
    }
    
    /**
     * @return Publicacao
     */
    public function getPublicacao()
    {
        return $this->publicacao;
    }

    /**
     * @return FolhaPagamento
     */
    public function getFolhaPagamento()
    {
        return $this->folhaPagamento;
    }

    /**
     * @return string
     */
    public function getParticipantes()
    {
        return $this->participantes;
    }
    
    /**
     * @return string
     */
    public function getDsJustificativa()
    {
        return $this->dsJustificativa;
    }
        
    /**
     * @return string
     */
    public function getSalvaEfecha()
    {
        return $this->salvaEfecha;
    }

    /**
     * @return array
     */
    public function getParticipantesAsArray()
    {
        return (is_array($this->participantes)) ?
            $this->participantes : explode(',', str_replace(' ', '', $this->participantes));
    }
    
    /**
     * @param FolhaPagamento $folhaPagamentoSuplementar
     */
    public function setFolhaPagamentoSuplementar(FolhaPagamento $folhaPagamentoSuplementar)
    {
        $this->folhaPagamentoSuplementar = $folhaPagamentoSuplementar;
        $this->dsJustificativa = $folhaPagamentoSuplementar->getDsJustificativa();
    }
    
    /**
     * @param PessoaPerfil $pessoaPerfil
     */
    public function setPessoaPerfil(PessoaPerfil $pessoaPerfil)
    {
        $this->pessoaPerfil = $pessoaPerfil;
    }
    
    /**
     * @param Publicacao $publicacao
     */
    public function setPublicacao(Publicacao $publicacao = null)
    {
        $this->publicacao = $publicacao;    
    }

    /**
     * @param FolhaPagamento $folhaPagamento
     */
    public function setFolhaPagamento(FolhaPagamento $folhaPagamento = null)
    {
        $this->folhaPagamento = $folhaPagamento;
        $this->publicacao = $folhaPagamento->getPublicacao();        
    }

    /**
     * @param string $participantes
     */
    public function setParticipantes($participantes)
    {
        $this->participantes = $participantes;
    }
    
    /**
     * @param string $dsJustificativa
     */
    public function setDsJustificativa($dsJustificativa)
    {
        $this->dsJustificativa = $dsJustificativa;
    }
        
    /**
     * @param string $salvaEfecha
     */
    public function setSalvaEfecha($salvaEfecha)
    {
        $this->salvaEfecha = $salvaEfecha;
    }

    /**
     * @return boolean
     */
    public function fechaFolha()
    {
        return $this->salvaEfecha === 'S';
    }
    
    /**
     * @param ExecutionContextInterface $context
     * 
     * @Assert\Callback
     */
    public function validateFields(ExecutionContextInterface $context)
    {
        if (!$this->getFolhaPagamentoSuplementar()) {
            if (!$this->getPublicacao()) {
                $context->buildViolation('Este valor não deve ser vazio.')
                    ->atPath('publicacao')
                    ->addViolation();
            }
            if (!$this->getFolhaPagamento()) {
                $context->buildViolation('Este valor não deve ser vazio.')
                    ->atPath('folhaPagamento')
                    ->addViolation();
            }
        }
    }
}
