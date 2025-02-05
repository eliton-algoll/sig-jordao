<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrgaoEmissor
 *
 * @ORM\Table(name="DBGERAL.TB_ORGAO_EMISSOR")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OrgaoEmissorRepository")
 */
class OrgaoEmissor extends AbstractEntity
{
    use \AppBundle\Traits\DeleteLogicoTrait;
    
    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(name="CO_ORGAO_EMISSOR", type="string", length=3, unique=true)
     */
    private $coOrgaoEmissor;

    /**
     * @var string
     *
     * @ORM\Column(name="DS_ORGAO_EMISSOR", type="string", length=60, unique=true)
     */
    private $dsOrgaoEmissor;

    /**
     * @var string
     *
     * @ORM\Column(name="SG_ORGAO_EMISSOR", type="string", length=10)
     */
    private $sgOrgaoEmissor;
    
    
    /**
     * Get coOrgaoEmissor
     *
     * @return string
     */
    public function getCoOrgaoEmissor()
    {
        return $this->coOrgaoEmissor;
    }

    /**
     * Get dsOrgaoEmissor
     *
     * @return string
     */
    public function getDsOrgaoEmissor()
    {
        return $this->dsOrgaoEmissor;
    }

    /**
     * Get sgOrgaoEmissor
     *
     * @return string
     */
    public function getSgOrgaoEmissor()
    {
        return $this->sgOrgaoEmissor;
    }
    
    /**
     * @return string
     */
    public function getSgAndDsOrgaoEmissor()
    {
        return $this->sgOrgaoEmissor . ' - ' . $this->dsOrgaoEmissor;
    }
}

