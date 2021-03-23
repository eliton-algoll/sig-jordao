<?php

namespace AppBundle\CommandBus;

use Symfony\Component\Validator\Constraints as Assert;

class FiltroRelatorioPagamentoCommand
{
    private $nuSipar;
    private $nuMes;
    private $stProjetoFolha;
    private $ufCampus;
    private $municipioCampus;
    private $instituicao;
    private $campus;
    private $ufSecretaria;
    private $municipioSecretaria;
    private $secretaria;
    
    public function __construct(Array $params = array())
    {
        foreach($params as $key => $value) {
            if(property_exists($this, $key)) {
                $method = 'set' . ucfirst($key);
                $this->$method($value);
            }
        }
    }


    public function getNuSipar()
    {
        return $this->nuSipar;
    }

    public function getNuMes()
    {
        return $this->nuMes;
    }

    public function getStProjetoFolha()
    {
        return $this->stProjetoFolha;
    }

    public function getUfCampus()
    {
        return $this->ufCampus;
    }

    public function getMunicipioCampus()
    {
        return $this->municipioCampus;
    }

    public function getInstituicao()
    {
        return $this->instituicao;
    }

    public function getCampus()
    {
        return $this->campus;
    }

    public function getUfSecretaria()
    {
        return $this->ufSecretaria;
    }

    public function getMunicipioSecretaria()
    {
        return $this->municipioSecretaria;
    }

    public function getSecretaria()
    {
        return $this->secretaria;
    }

    public function setNuSipar($nuSipar)
    {
        $this->nuSipar = $nuSipar;
        return $this;
    }

    public function setNuMes($nuMes)
    {
        $this->nuMes = $nuMes;
        return $this;
    }

    public function setStProjetoFolha($stProjetoFolha)
    {
        $this->stProjetoFolha = $stProjetoFolha;
        return $this;
    }

    public function setUfCampus(\AppBundle\Entity\Uf $uf_campus)
    {
        $this->ufCampus = $uf_campus;
        return $this;
    }

    public function setMunicipioCampus($municipioCampus)
    {
        $this->municipioCampus = $municipioCampus;
        return $this;
    }

    public function setInstituicao($instituicao)
    {
        $this->instituicao = $instituicao;
        return $this;
    }

    public function setCampus($campus)
    {
        $this->campus = $campus;
        return $this;
    }
    
    public function setUfSecretaria(\AppBundle\Entity\Uf $ufSecretaria)
    {
        $this->ufSecretaria = $ufSecretaria;
        return $this;
    }

    public function setMunicipioSecretaria($municipioSecretaria)
    {
        $this->municipioSecretaria = $municipioSecretaria;
        return $this;
    }

    public function setSecretaria($secretaria)
    {
        $this->secretaria = $secretaria;
        return $this;
    }


}
