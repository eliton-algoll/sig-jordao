<?php

namespace AppBundle\Entity;

use AppBundle\Repository\IdentidadeGeneroRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * IdentidadeGenero
 *
 * @ORM\Table(name="DBGERAL.TB_IDENTIDADE_GENERO")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\IdentidadeGeneroRepository")
 */
class IdentidadeGenero extends AbstractEntity
{
    /**
     * @var string
     *
     * @ORM\Column(name="CO_IDENTIDADE_GENERO", type="string", length=1, nullable=false)
     * @ORM\Id
     */
    private $coIdentidadeGenero;

    /**
     * @var string
     *
     * @ORM\Column(name="DS_IDENTIDADE_GENERO", type="string", length=10, nullable=true)
     */
    private $dsIdentidadeGenero;
    /**
     * @var string
     *
     * @ORM\Column(name="ST_REGISTRO_ATIVO", type="string", length=1, nullable=true)
     */
    private $stRegistroAtivo;


    /**
     * @return string
     */
    public function getCoIdentidadeGenero()
    {
        return $this->coIdentidadeGenero;
    }

    /**
     * @return string
     */
    public function getDsIdentidadeGenero()
    {
        return $this->dsIdentidadeGenero;
    }

    /**
     * @param string $dsIdentidadeGenero
     *
     * @return IdentidadeGenero
     */
    public function setDsIdentidadeGenero($dsIdentidadeGenero)
    {
        $this->dsIdentidadeGenero = $dsIdentidadeGenero;
        return $this;
    }


    /**
     * Set stRegistroAtivo
     *
     * @param string $stRegistroAtivo
     * @return IdentidadeGenero
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