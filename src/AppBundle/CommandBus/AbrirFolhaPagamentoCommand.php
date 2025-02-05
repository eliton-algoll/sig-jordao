<?php

namespace AppBundle\CommandBus;

use Symfony\Component\Validator\Constraints as Assert;

class AbrirFolhaPagamentoCommand extends FolhaPagamentoCommand
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $publicacao;
    
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Type(
     *     type="numeric"
     * )
     */
    private $nuAno;
    
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Type(
     *     type="numeric"
     * )
     */
    private $nuMes;
    
    /**
     * @return string
     */
    public function getPublicacao()
    {
        return $this->publicacao;
    }

    /**
     * @return string
     */
    public function getNuAno()
    {
        return $this->nuAno;
    }

    /**
     * @return string
     */
    public function getNuMes()
    {
        return $this->nuMes;
    }
    
    /**
     * @param string $publicacao
     * @return AbrirFolhaPagamentoCommand
     */
    public function setPublicacao($coPublicacao)
    {
        $this->publicacao = $coPublicacao;
        return $this;
    }

    /**
     * @param string $nuAno
     * @return AbrirFolhaPagamentoCommand
     */
    public function setNuAno($nuAno)
    {
        $this->nuAno = $nuAno;
        return $this;
    }

    /**
     * @param string $nuMes
     * @return AbrirFolhaPagamentoCommand
     */
    public function setNuMes($nuMes)
    {
        $this->nuMes = $nuMes;
        return $this;
    }

}
