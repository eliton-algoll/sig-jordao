<?php

namespace App\Traits;

use Doctrine\ORM\Mapping as ORM;

trait DeleteLogicoTrait
{
    /**
     * @var string
     *
     * @ORM\Column(name="ST_REGISTRO_ATIVO", type="string", length=1, nullable=false)
     */
    private $stRegistroAtivo;
   
    /**
     * @return string
     */
    public function getStRegistroAtivo()
    {
        return $this->stRegistroAtivo;
    }
    
    /**
     * ativa objeto
     */
    public function ativar()
    {
        $this->stRegistroAtivo = 'S';
        return $this;
    }

    /**
     * ativa objeto
     */
    public function inativar()
    {
        $this->stRegistroAtivo = 'N';
        return $this;
    }

    /**
     * @return boolean
     */
    public function isAtivo()
    {
        return $this->stRegistroAtivo === 'S';
    }    
    
    /**
     * @return boolean
     */
    public function isInativo()
    {
        return $this->stRegistroAtivo === 'N';
    }
    
    /**
     * 
     * @return string
     */
    public function getDescricaoSituacaoRegistro()
    {
        return ($this->isAtivo()) ? 'Ativo' : 'Inativo';
    }
}