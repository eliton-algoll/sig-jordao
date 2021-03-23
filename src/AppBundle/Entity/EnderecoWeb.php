<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EnderecoWeb
 *
 * @ORM\Table(name="DBPET.TB_ENDERECO_WEB")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EnderecoWebRepository")
 */
class EnderecoWeb extends AbstractEntity
{
    use \AppBundle\Traits\DeleteLogicoTrait;
    use \AppBundle\Traits\DataInclusaoTrait;
    
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\Column(name="CO_SEQ_ENDERECO_WEB", type="integer", unique=true)
     * @ORM\SequenceGenerator(sequenceName="DBPET.SQ_ENDERECWEB_COSEQENDERECOWEB", allocationSize=1, initialValue=1)
     * 
     */
    private $coSeqEnderecoWeb;

    /**
     * @var Pessoa
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Pessoa", inversedBy="enderecosWeb")
     * @ORM\JoinColumn(name="NU_CPF_CNPJ_PESSOA", referencedColumnName="NU_CPF_CNPJ_PESSOA")
     */
    private $pessoa;

    /**
     * @var string
     *
     * @ORM\Column(name="DS_ENDERECO_WEB", type="string", length=255, unique=true)
     */
    private $dsEnderecoWeb;

    /**
     * 
     * @param Pessoa $pessoa
     * @param string $dsEnderecoWeb
     */
    public function __construct(Pessoa $pessoa, $dsEnderecoWeb)
    {
        $this->pessoa           = $pessoa;
        $this->dsEnderecoWeb    = $dsEnderecoWeb;
        $this->stRegistroAtivo  = 'S';
        $this->dtInclusao       = new \DateTime();
    }
    
    /**
     * Set coSeqEnderecoWeb
     *
     * @param integer $coSeqEnderecoWeb
     *
     * @return EnderecoWeb
     */
    public function setCoSeqEnderecoWeb($coSeqEnderecoWeb)
    {
        $this->coSeqEnderecoWeb = $coSeqEnderecoWeb;

        return $this;
    }

    /**
     * Get coSeqEnderecoWeb
     *
     * @return int
     */
    public function getCoSeqEnderecoWeb()
    {
        return $this->coSeqEnderecoWeb;
    }

    /**
     * Set pessoa
     *
     * @param Pessoa $pessoa
     *
     * @return EnderecoWeb
     */
    public function setPessoa(Pessoa $pessoa)
    {
        $this->pessoa = $pessoa;

        return $this;
    }

    /**
     * Get pessoa
     *
     * @return string
     */
    public function getPessoa()
    {
        return $this->pessoa;
    }

    /**
     * Set dsEnderecoWeb
     *
     * @param string $dsEnderecoWeb
     *
     * @return EnderecoWeb
     */
    public function setDsEnderecoWeb($dsEnderecoWeb)
    {
        $this->dsEnderecoWeb = $dsEnderecoWeb;

        return $this;
    }

    /**
     * Get dsEnderecoWeb
     *
     * @return string
     */
    public function getDsEnderecoWeb()
    {
        return $this->dsEnderecoWeb;
    }
    
    /**
     * @inheritdoc
     */
    public function __toString()
    {
        return $this->getDsEnderecoWeb();
    }
    
    /**
     * 
     * @return array
     */
    public function __toArray()
    {
        return array(
            'coSeqEnderecoWeb' => $this->getCoSeqEnderecoWeb(),
            'dsEnderecoWeb' => $this->getDsEnderecoWeb()
        );
    }
}

