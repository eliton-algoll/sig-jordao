<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Instituicao
 *
 * @ORM\Table(name="DBPET.TB_INSTITUICAO")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\InstituicaoRepository")
 */
class Instituicao extends AbstractEntity
{
    use \AppBundle\Traits\DeleteLogicoTrait;
    use \AppBundle\Traits\DataInclusaoTrait;    

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\Column(name="CO_SEQ_INSTITUICAO", type="integer")
     * @ORM\SequenceGenerator(sequenceName="DBPET.SQ_INSTITUICAO_COSEQINSTITUICA", allocationSize=1, initialValue=1)
     */
    private $coSeqInstituicao;

    /**
     * @var PessoaJuridica
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\PessoaJuridica")
     * @ORM\JoinColumn(name="NU_CNPJ", referencedColumnName="NU_CNPJ")
     */
    private $pessoaJuridica;

    /**
     * @var Municipio
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Municipio")
     * @ORM\JoinColumn(name="CO_MUNICIPIO_IBGE", referencedColumnName="CO_MUNICIPIO_IBGE")
     */
    private $municipio;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="NO_INSTITUICAO_PROJETO", type="string", length=200)
     */
    private $noInstituicaoProjeto;

    /**
     * @param PessoaJuridica $pessoaJuridica
     * @param Municipio $municipio
     * @param string $noInstituicaoProjeto
     */
    public function __construct($pessoaJuridica, $municipio, $noInstituicaoProjeto)
    {
        $this->municipio = $municipio;
        $this->pessoaJuridica = $pessoaJuridica;
        $this->noInstituicaoProjeto = $noInstituicaoProjeto;
        $this->stRegistroAtivo = 'S';
        $this->dtInclusao = new \DateTime();
    }

    /**
     * Get coSeqInstituicao
     *
     * @return int
     */
    public function getCoSeqInstituicao()
    {
        return $this->coSeqInstituicao;
    }

    /**
     * Get pessoaJuridica
     *
     * @return PessoaJuridica
     */
    public function getPessoaJuridica()
    {
        return $this->pessoaJuridica;
    }

    /**
     * Get municipio
     *
     * @return Municipio
     */
    public function getMunicipio()
    {
        return $this->municipio;
    }
    
    /**
     * @return string
     */
    public function getNoInstituicaoProjeto()
    {
        return $this->noInstituicaoProjeto;
    }

    /**
     * @param PessoaJuridica $pessoaJuridica
     */
    public function setPessoaJuridica($pessoaJuridica)
    {
        $this->pessoaJuridica = $pessoaJuridica;
    }

    /**
     * @param Municipio $municipio
     */
    public function setMunicipio($municipio)
    {
        $this->municipio = $municipio;
    }

    /**
     * @param string $noInstituicaoProjeto
     */
    public function setNoInstituicaoProjeto($noInstituicaoProjeto)
    {
        $this->noInstituicaoProjeto = $noInstituicaoProjeto;
    }

    /**
     * @return string
     */
    public function getStRegistroAtivo()
    {
        return $this->stRegistroAtivo;
    }

    /**
     * @param string $stRegistroAtivo
     */
    public function setStRegistroAtivo($stRegistroAtivo)
    {
        $this->stRegistroAtivo = $stRegistroAtivo;
    }
}
