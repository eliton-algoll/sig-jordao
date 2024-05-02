<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * PessoaGenero
 *
 * @ORM\Table(name="DBPET.TB_PESSOA_GENERO")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PessoaGeneroRepository")
 */
class PessoaGenero extends AbstractEntity
{
    use \AppBundle\Traits\DeleteLogicoTrait;
    use \AppBundle\Traits\DataInclusaoTrait;
    
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\Column(name="CO_SEQ_PESSOA_GENERO", type="integer")
     * @ORM\SequenceGenerator(sequenceName="DBPET.SQ_PROJPESSOA_COSEQPROJPESSOA", allocationSize=1, initialValue=1)
     */
    private $coSeqPessoaGenero;

    /**
     * @var IdentidadeGenero
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\IdentidadeGenero")
     * @ORM\JoinColumn(name="CO_IDENTIDADE_GENERO", referencedColumnName="CO_IDENTIDADE_GENERO")
     */
    private $identidadeGenero;
    
    /**
     * @var PessoaFisica
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\PessoaFisica", cascade={"persist"})
     * @ORM\JoinColumn(name="NU_CPF", referencedColumnName="NU_CPF")
     */
    private $pessoaFisica;
    
    /**
     * @var ArrayCollection<ProjetoPessoa>
     * 
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ProjetoPessoa", mappedBy="pessoaGenero", cascade={"persist"})
     */
    private $projetosPessoas;
    
    /**
     * @param IdentidadeGenero $identidadeGenero
     * @param PessoaFisica $pessoaFisica
     */
    public function __construct(IdentidadeGenero $identidadeGenero, PessoaFisica $pessoaFisica)
    {
        $this->identidadeGenero = $identidadeGenero;
        $this->pessoaFisica = $pessoaFisica;
        $this->stRegistroAtivo = 'S';
        $this->dtInclusao = new \DateTime();
        $this->projetosPessoas = new ArrayCollection();
    }

    /**
     * @return IdentidadeGenero
     */
    public function getIdentidadeGenero()
    {
        return $this->identidadeGenero;
    }


    /**
     * Get pessoaFisica
     *
     * @return PessoaFisica
     */
    public function getPessoaFisica()
    {
        return $this->pessoaFisica;
    }
    
    /**
     * @return ArrayCollection<ProjetoPessoa>
     */
    public function getProjetosPessoas()
    {
        return $this->projetosPessoas;
    }
}