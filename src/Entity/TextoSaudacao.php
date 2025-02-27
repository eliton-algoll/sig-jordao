<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TextoSaudacao
 *
 * @ORM\Table(name="DBPETINFOSD.TB_TEXTO_SAUDACAO")
 * @ORM\Entity(repositoryClass="App\Repository\TextoSaudacaoRepository")
 */
class TextoSaudacao extends AbstractEntity
{
    use \App\Traits\DeleteLogicoTrait;
    use \App\Traits\DataInclusaoTrait;

    /**
     * @var string
     *
     * @ORM\Id
     * * @ORM\GeneratedValue(strategy="AUTO")
     * * @ORM\Column(name="CO_SEQ_TEXTO_SAUDACAO", type="integer")
     * * @ORM\SequenceGenerator(sequenceName="DBPETINFOSD.SQ_TEXTOSAUD_COSEQTEXTOSAUD", allocationSize=1, initialValue=1)
     */
    private $coSeqTextoSaudacao;

    /**
     * @var string
     *
     * @ORM\Column(name="DS_TEXTO_SAUDACAO", type="string", length=4000)
     */
    private $dsTextoSaudacao;

    /**
     * @return string
     */
    public function getCoSeqTextoSaudacao()
    {
        return $this->coSeqTextoSaudacao;
    }

    /**
     * @param string $coSeqTextoSaudacao
     */
    public function setCoSeqTextoSaudacao($coSeqTextoSaudacao)
    {
        $this->coSeqTextoSaudacao = $coSeqTextoSaudacao;
    }

    /**
     * @return string
     */
    public function getDsTextoSaudacao()
    {
        return $this->dsTextoSaudacao;
    }

    /**
     * @param string $dsTextoSaudacao
     */
    public function setDsTextoSaudacao($dsTextoSaudacao)
    {
        $this->dsTextoSaudacao = $dsTextoSaudacao;
    }


}
