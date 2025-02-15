<?php

namespace App\CommandBus;

use App\Entity\Perfil;
use App\Entity\Publicacao;
use App\Entity\ValorBolsaPrograma;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

final class CadastrarValorBolsaCommand
{
    /**
     *
     * @var ValorBolsaPrograma 
     */
    private $valorBolsaPrograma;
    
    /**
     *
     * @var Publicacao
     * 
     * @Assert\NotBlank()
     */
    private $publicacao;
    
    /**
     *
     * @var Perfil 
     * 
     * @Assert\NotBlank()
     */
    private $tipoParticipante;
    
    /**
     *
     * @var float
     * 
     * @Assert\NotBlank()     
     * @Assert\GreaterThan(
     *      value = 0,
     *      message = "O valor de bolsa informado deverá ser maior que zero."
     * )
     */
    private $vlBolsa;
    
    /**
     *
     * @var string
     * 
     * @Assert\NotBlank()
     * @Assert\Regex(
     *      pattern = "/^(0[1-9]|1[0-2])\/[0-9]{4}/",
     *      message = "Formato inválido."
     * )
     */
    private $inicioVigencia;
    
    private $dtInclusao;
    
    /**
     * 
     * @return ValorBolsaPrograma
     */
    public function getValorBolsaPrograma()
    {
        return $this->valorBolsaPrograma;
    }

    /**
     * 
     * @return Publicacao
     */
    public function getPublicacao()
    {
        return $this->publicacao;
    }

    /**
     * 
     * @return Perfil
     */
    public function getTipoParticipante()
    {
        return $this->tipoParticipante;
    }

    /**
     * 
     * @return string
     */
    public function getVlBolsa()
    {
        return $this->vlBolsa;
    }
    
    /**
     * 
     * @return float
     */
    public function getFloatVlBolsa()
    {
        return floatval(str_replace(',', '.', str_replace('.', '', $this->vlBolsa)));       
    }

    /**
     * 
     * @return string
     */
    public function getInicioVigencia()
    {
        return $this->inicioVigencia;
    }
    
    /**
     * 
     * @return \DateTime
     */
    public function getDtInclusao()
    {
        return $this->dtInclusao;
    }
        
    /**
     * 
     * @return string
     */
    public function getNuMesInicioVigencia()
    {
        return substr($this->inicioVigencia, 0, 2);
    }
            
    /**
     * 
     * @return string
     */
    public function getNuAnoInicioVigencia()
    {
        return substr($this->inicioVigencia, 3, 4);
    }

    /**
     * 
     * @param ValorBolsaPrograma $valorBolsaPrograma
     */
    public function setValorBolsaPrograma(ValorBolsaPrograma $valorBolsaPrograma)
    {
        $this->valorBolsaPrograma = $valorBolsaPrograma;
        $this->publicacao = $valorBolsaPrograma->getPublicacao();
        $this->tipoParticipante = $valorBolsaPrograma->getPerfil();
        $this->vlBolsa = (string) number_format($valorBolsaPrograma->getVlBolsa(), 2, ',', '.');
        $this->inicioVigencia = $valorBolsaPrograma->getInicoVigencia();
        $this->dtInclusao = $valorBolsaPrograma->getDtInclusao();
    }

    /**
     * 
     * @param Publicacao $publicacao
     */
    public function setPublicacao(Publicacao $publicacao = null)
    {
        $this->publicacao = $publicacao;
    }

    /**
     * 
     * @param Perfil $tipoParticipante
     */
    public function setTipoParticipante(Perfil $tipoParticipante = null)
    {
        $this->tipoParticipante = $tipoParticipante;
    }

    /**
     * 
     * @param string $vlBolsa
     */
    public function setVlBolsa($vlBolsa)
    {
        $this->vlBolsa = $vlBolsa;
    }

    /**
     * 
     * @param string $inicioVigencia
     */
    public function setInicioVigencia($inicioVigencia)
    {
        $this->inicioVigencia = $inicioVigencia;
    }
    
    public function setDtInclusao($dtInclusao)
    {
        $this->dtInclusao = $dtInclusao;
    }
    
    /**
     * 
     * @return boolean
     */
    public function isVigente()
    {
        $now = new \DateTime();
        
        return $this->getNuAnoInicioVigencia() . $this->getNuMesInicioVigencia() == $now->format('Ym');
    }
        
    /**
     * 
     * @param ExecutionContextInterface $context
     * 
     * @Assert\Callback()
     */
    public function validateInicoVigencia(ExecutionContextInterface $context)
    {
        $now = new \DateTime();
        $inicioVigencia = \DateTime::createFromFormat(
            'Ymd', $this->getNuAnoInicioVigencia() . $this->getNuMesInicioVigencia() . '01'
        );
        
        if ($inicioVigencia instanceof \DateTime && $inicioVigencia->format('Ym') < $now->format('Ym')) {            
            $context->buildViolation('O início de vigência (mês/ano) não poderá ser menor que a data corrente.')
                ->atPath('inicioVigencia')
                ->addViolation();
        }
    }
}
