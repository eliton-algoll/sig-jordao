<?php

namespace AppBundle\CommandBus;

use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as AppAssert;

class FiltroRelatorioPagamentoNaoAutorizadoCommand
{
    /**
     *
     * @var string
     * 
     * @AppAssert\Cpfcnpj(
     *      message_cpf = "CPF inválido",
     *      aceitar = "cpf"
     * )
     */
    private $nuCpf;
    private $nuMes;
    private $nuAno;
    private $noPessoa;
    private $perfil;
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


    public function getNuCpf()
    {
        return $this->nuCpf;
    }

    public function getNuMes()
    {
        return $this->nuMes;
    }

    public function getNuAno()
    {
        return $this->nuAno;
    }

    public function getNoPessoa()
    {
        return $this->noPessoa;
    }

    public function getPerfil()
    {
        return $this->perfil;
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

    public function setNuCpf($nuCpf)
    {
        $this->nuCpf = $nuCpf;
        return $this;
    }

    public function setNuMes($nuMes)
    {
        $this->nuMes = $nuMes;
        return $this;
    }

    public function setNuAno($nuAno)
    {
        $this->nuAno = $nuAno;
        return $this;
    }

    public function setNoPessoa($noPessoa)
    {
        $this->noPessoa = $noPessoa;
        return $this;
    }

    public function setPerfil($perfil)
    {
        $this->perfil = $perfil;
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
