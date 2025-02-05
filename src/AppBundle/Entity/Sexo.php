<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sexo
 *
 * @ORM\Table(name="DBGERAL.TB_SEXO")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SexoRepository")
 */
class Sexo extends AbstractEntity
{
    /**
     * @var string
     *
     * @ORM\Column(name="CO_SEXO", type="string", length=1, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="TB_SEXO_CO_SEXO_seq", allocationSize=1, initialValue=1)
     */
    private $coSexo;

    /**
     * @var string
     *
     * @ORM\Column(name="DS_SEXO", type="string", length=10, nullable=true)
     */
    private $dsSexo;

    /**
     * @var string
     *
     * @ORM\Column(name="ST_CADSUS", type="string", length=1, nullable=true)
     */
    private $stCadsus;

    /**
     * @var string
     *
     * @ORM\Column(name="ST_REGISTRO_ATIVO", type="string", length=1, nullable=true)
     */
    private $stRegistroAtivo;


    /**
     * Get coSexo
     *
     * @return string 
     */
    public function getCoSexo()
    {
        return $this->coSexo;
    }

    /**
     * Set dsSexo
     *
     * @param string $dsSexo
     * @return Sexo
     */
    public function setDsSexo($dsSexo)
    {
        $this->dsSexo = $dsSexo;
    
        return $this;
    }

    /**
     * Get dsSexo
     *
     * @return string 
     */
    public function getDsSexo()
    {
        return $this->dsSexo;
    }

    /**
     * Set stCadsus
     *
     * @param string $stCadsus
     * @return Sexo
     */
    public function setStCadsus($stCadsus)
    {
        $this->stCadsus = $stCadsus;
    
        return $this;
    }

    /**
     * Get stCadsus
     *
     * @return string 
     */
    public function getStCadsus()
    {
        return $this->stCadsus;
    }

    /**
     * Set stRegistroAtivo
     *
     * @param string $stRegistroAtivo
     * @return Sexo
     */
    public function setStRegistroAtivo($stRegistroAtivo)
    {
        $this->stRegistroAtivo = $stRegistroAtivo;
    
        return $this;
    }

    /**
     * Get stRegistroAtivo
     *
     * @return string 
     */
    public function getStRegistroAtivo()
    {
        return $this->stRegistroAtivo;
    }
}