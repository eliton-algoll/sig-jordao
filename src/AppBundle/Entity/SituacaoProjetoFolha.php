<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SituacaoProjetoFolha
 *
 * @ORM\Table(name="DBPET.TB_SITUACAO_PROJETO_FOLHA")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SituacaoProjetoFolhaRepository")
 */
class SituacaoProjetoFolha extends AbstractEntity
{
    use \AppBundle\Traits\DeleteLogicoTrait;
    use \AppBundle\Traits\DataInclusaoTrait;    

    const AUTORIZADA = 1;
    const HOMOLOGADA = 2;
    const CANCELADA = 3;
    const ENVIADA_PAGAMENTO = 4;
    const ORDEM_BANCARIA_EMITIDA = 5;
    
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="CO_SEQ_SITUACAO_PROJ_FOLHA", type="integer")
     * @ORM\SequenceGenerator(sequenceName="DBPET.SQ_SITPROJFL_COSEQSTIPROJFOLHA", allocationSize=1, initialValue=1)
     */
    private $coSeqSituacaoProjFolha;

    /**
     * @var string
     *
     * @ORM\Column(name="DS_SITUACAO_PROJETO_FOLHA", type="string", length=100)
     */
    private $dsSituacaoProjetoFolha;

    /**
     * @param string $dsSituacaoProjetoFolha
     */
    public function __construct($dsSituacaoProjetoFolha)
    {
        $this->dsSituacaoProjetoFolha = $dsSituacaoProjetoFolha;
        $this->dtInclusao = new \DateTime();
        $this->stRegistroAtivo = 'S';        
    }

    /**
     * Get coSeqSituacaoProjFolha
     *
     * @return int
     */
    public function getCoSeqSituacaoProjFolha()
    {
        return $this->coSeqSituacaoProjFolha;
    }

    /**
     * Get dsSituacaoProjetoFolha
     *
     * @return string
     */
    public function getDsSituacaoProjetoFolha()
    {
        return $this->dsSituacaoProjetoFolha;
    }
}