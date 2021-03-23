<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TipoQuantitativoPublicacao
 *
 * @ORM\Table(name="DBPET.TB_TIPOQUANTITATIVO_PUBLICACAO")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TipoQuantitativoPublicacaoRepository")
 */
class TipoQuantitativoPublicacao extends AbstractEntity
{
    use \AppBundle\Traits\DeleteLogicoTrait;
    use \AppBundle\Traits\DataInclusaoTrait;        

    const QTD_MINIMA_BOLSISTAS_GRUPO = 1;
    const QTD_MAXIMA_BOLSISTAS_GRUPO = 2;
    const QTD_TOTAL_BOLSAS_PROJETO = 3;
    
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\Column(name="CO_SEQ_TIPOQUANTID_PUBLICACAO", type="integer")
     * @ORM\SequenceGenerator(sequenceName="DBPET.SQ_TPQTDPUBLI_COSEQTPQTDPUBLIC", allocationSize=1, initialValue=1)
     */
    private $coSeqTipoquantidPublicacao;

    /**
     * @var string
     *
     * @ORM\Column(name="DS_TIPOQUANTIDADE_PUBLICACAO", type="string", length=60)
     */
    private $dsTipoquantidadePublicacao;

    /**
     * @param string $dsTipoquantidadePublicacao
     */
    public function __construct($dsTipoquantidadePublicacao)
    {
        $this->dsTipoquantidadePublicacao = $dsTipoquantidadePublicacao;
        $this->stRegistroAtivo = 'S';
        $this->dtInclusao = new \DateTime();
    }
    
    /**
     * @return integer
     */
    public function getCoSeqTipoquantidPublicacao()
    {
        return $this->coSeqTipoquantidPublicacao;
    }

    /**
     * @return string
     */
    public function getDsTipoquantidadePublicacao()
    {
        return $this->dsTipoquantidadePublicacao;
    }
}