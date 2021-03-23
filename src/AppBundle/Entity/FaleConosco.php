<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="DBPET.TB_FALE_CONOSCO")
 * @ORM\Entity(repositoryClass="\AppBundle\Repository\FaleConoscoRepository")
 */
class FaleConosco extends AbstractEntity
{
    use \AppBundle\Traits\DataInclusaoTrait;
    use \AppBundle\Traits\DeleteLogicoTrait;
    
    /**
     *
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(name="CO_SEQ_FALE_CONOSCO", type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="DBPET.SQ_FALECONOSCO_COSEQFALECONOSC", allocationSize=1, initialValue=1)
     */
    private $coSeqFaleConosco;
    
    /**
     *
     * @var string
     *
     * @ORM\Column(name="NO_PESSOA", type="string", length=100, nullable=false)
     */
    private $noPessoa;
    
    /**
     *
     * @var string
     *
     * @ORM\Column(name="DS_EMAIL", type="string", length=60, nullable=false)
     */
    private $dsEmail;
    
    /**
     *
     * @var TipoAssunto
     *
     * @ORM\ManyToOne(targetEntity="\AppBundle\Entity\TipoAssunto")
     * @ORM\JoinColumn(name="CO_TIPO_ASSUNTO", referencedColumnName="CO_SEQ_TIPO_ASSUNTO", nullable=false)
     */
    private $tipoAssunto;
    
    /**
     *
     * @var string
     *
     * @ORM\Column(name="DS_ASSUNTO", type="string", length=100, nullable=true)
     */
    private $dsAssunto;
    
    /**
     *
     * @var string
     *
     * @ORM\Column(name="DS_MENSAGEM", type="string", length=4000, nullable=false)
     */
    private $dsMensagem;
    
    /**
     *
     * @param string $noPessoa
     * @param string $dsEmail
     * @param \AppBundle\Entity\TipoAssunto $tipoAssunto
     * @param string $dsAssunto
     * @param string $dsMensagem
     */
    public function __construct($noPessoa, $dsEmail, TipoAssunto $tipoAssunto, $dsAssunto, $dsMensagem)
    {
        $this->noPessoa = $noPessoa;
        $this->dsEmail = $dsEmail;
        $this->tipoAssunto = $tipoAssunto;
        $this->dsAssunto = $dsAssunto;
        $this->dsMensagem = $dsMensagem;
        $this->stRegistroAtivo = 'S';
        $this->dtInclusao = new \DateTime();
    }

    /**
     *
     * @return integer
     */
    public function getCoSeqFaleConosco()
    {
        return $this->coSeqFaleConosco;
    }

    /**
     *
     * @return string
     */
    public function getNoPessoa()
    {
        return $this->noPessoa;
    }

    /**
     *
     * @return string
     */
    public function getDsEmail()
    {
        return $this->dsEmail;
    }

    /**
     *
     * @return \AppBundle\Entity\TipoAssunto
     */
    public function getTipoAssunto()
    {
        return $this->tipoAssunto;
    }

    /**
     *
     * @return string
     */
    public function getDsAssunto()
    {
        return $this->dsAssunto;
    }

    /**
     *
     * @return string
     */
    public function getDsMensagem()
    {
        return $this->dsMensagem;
    }
    
    /**
     * 
     * @return string
     */
    public function getAssuntoCompleto()
    {
        return ($this->getDsAssunto()) ?
            $this->getTipoAssunto()->getDsTipoAssunto() . ' - ' . $this->getDsAssunto() :
            $this->getTipoAssunto()->getDsTipoAssunto();
    }
}
