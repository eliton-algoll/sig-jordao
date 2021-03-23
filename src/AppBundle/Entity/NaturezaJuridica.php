<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NaturezaJuridica
 *
 * @ORM\Table(name="DBPESSOA.TB_PESSOA_FISICA")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\NaturezaJuridicaRepository")
 */
class NaturezaJuridica extends AbstractEntity
{
    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(name="CO_NATUREZA_JURIDICA", type="string", length=11, nullable=false)
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $coNaturezaJuridica;

    /**
     * @var string
     *
     * @ORM\Column(name="DS_NATUREZA_JURIDICA", type="string", length=80)
     */
    private $dsNaturezaJuridica;

    /**
     * @var string
     *
     * @ORM\Column(name="ST_REGISTRO_ATIVO", type="string", length=1)
     */
    private $stRegistroAtivo;

    /**
     * Set dsNaturezaJuridica
     *
     * @param string $dsNaturezaJuridica
     *
     * @return NaturezaJuridica
     */
    public function setDsNaturezaJuridica($dsNaturezaJuridica)
    {
        $this->dsNaturezaJuridica = $dsNaturezaJuridica;

        return $this;
    }

    /**
     * Get dsNaturezaJuridica
     *
     * @return string
     */
    public function getDsNaturezaJuridica()
    {
        return $this->dsNaturezaJuridica;
    }

    /**
     * Set coNaturezaJuridica
     *
     * @param string $coNaturezaJuridica
     *
     * @return NaturezaJuridica
     */
    public function setCoNaturezaJuridica($coNaturezaJuridica)
    {
        $this->coNaturezaJuridica = $coNaturezaJuridica;

        return $this;
    }

    /**
     * Get coNaturezaJuridica
     *
     * @return string
     */
    public function getCoNaturezaJuridica()
    {
        return $this->coNaturezaJuridica;
    }

    /**
     * Set stRegistroAtivo
     *
     * @param string $stRegistroAtivo
     *
     * @return NaturezaJuridica
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

