<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * QuantidadePublicacao
 *
 * @ORM\Table(name="DBPET.TB_QUANTIDADE_PUBLICACAO")
 * @ORM\Entity(repositoryClass="App\Repository\QuantidadePublicacaoRepository")
 */
class QuantidadePublicacao extends AbstractEntity
{
    use \App\Traits\DeleteLogicoTrait;
    use \App\Traits\DataInclusaoTrait;    
    
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\Column(name="CO_SEQ_QUANTIDADE_PUBLICACAO", type="integer")
     * @ORM\SequenceGenerator(sequenceName="DBPET.SQ_QUANTDPUBLIC_COSEQQTDPUBLIC", allocationSize=1, initialValue=1)
     */
    private $coSeqQuantidadePublicacao;

    /**
     * @var Publicacao
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Publicacao", inversedBy="quantidades")
     * @ORM\JoinColumn(name="CO_PUBLICACAO", referencedColumnName="CO_SEQ_PUBLICACAO")
     */
    private $publicacao;

    /**
     * @var TipoQuantitativoPublicacao
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\TipoQuantitativoPublicacao")
     * @ORM\JoinColumn(name="CO_TIPOQUANTID_PUBLICACAO", referencedColumnName="CO_SEQ_TIPOQUANTID_PUBLICACAO")
     */
    private $tipoQuantitativoPublicacao;

    /**
     * @var int
     *
     * @ORM\Column(name="QT_PARTICIPANTE", type="integer")
     */
    private $qtParticipante;

    /**
     * @param Publicacao $publicacao
     * @param TipoQuantitativoPublicacao $tipoQuantitativoPublicacao
     * @param integer $qtParticipante
     */
    public function __construct(
        Publicacao $publicacao,
        TipoQuantitativoPublicacao $tipoQuantitativoPublicacao,
        $qtParticipante
    ) {
        $this->publicacao = $publicacao;
        $this->tipoQuantitativoPublicacao = $tipoQuantitativoPublicacao;
        $this->qtParticipante = $qtParticipante;
        $this->stRegistroAtivo = 'S';
        $this->dtInclusao = new \DateTime();
    }

    /**
     * @return integer
     */
    public function getCoSeqQuantidadePublicacao()
    {
        return $this->coSeqQuantidadePublicacao;
    }

    /**
     * @return Publicacao
     */
    public function getPublicacao()
    {
        return $this->publicacao;
    }

    /**
     * 
     * @return TipoQuantitativoPublicacao
     */
    public function getTipoQuantitativoPublicacao()
    {
        return $this->tipoQuantitativoPublicacao;
    }

    /**
     * @return integer
     */
    public function getQtParticipante()
    {
        return $this->qtParticipante;
    }
    
    /**
     * @param integer $qtParticipante
     * @return QuantidadePublicacao
     */
    public function setQtParticipante($qtParticipante)
    {
        $this->qtParticipante = $qtParticipante;
        return $this;
    }
}