<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SituacaoFolha
 *
 * @ORM\Table(name="DBPET.TB_SITUACAO_FOLHA")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SituacaoFolhaRepository")
 */
class SituacaoFolha extends AbstractEntity
{
    use \AppBundle\Traits\DeleteLogicoTrait;
    use \AppBundle\Traits\DataInclusaoTrait;    
    
    const ABERTA = 1;
    const FECHADA = 2;
    const HOMOLOGADA = 3;
    const ENVIADA_FUNDO = 4;
    const ORDEM_BANCARIA_EMITIDA = 5;
    const FINALIZADA = 6;
    const CANCELADA = 7;
    
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="CO_SEQ_SITUACAO_FOLHA", type="integer")
     * @ORM\SequenceGenerator(sequenceName="DBPET.SQ_SITUAFOL_COSEQSITUACAOFOLHA", allocationSize=1, initialValue=1)
     */
    private $coSeqSituacaoFolha;

    /**
     * @var string
     *
     * @ORM\Column(name="DS_SITUACAO_FOLHA", type="string", length=100)
     */
    private $dsSituacaoFolha;

    /**
     * @param string $dsSituacaoFolha
     */
    public function __construct($dsSituacaoFolha)
    {
        $this->dsSituacaoFolha = $dsSituacaoFolha;
        $this->dtInclusao = new \DateTime();
        $this->stRegistroAtivo = 'S';        
    }
    
    /**
     * Get coSeqSituacaoFolha
     *
     * @return int
     */
    public function getCoSeqSituacaoFolha()
    {
        return $this->coSeqSituacaoFolha;
    }

    /**
     * Get dsSituacaoFolha
     *
     * @return string
     */
    public function getDsSituacaoFolha()
    {
        return $this->dsSituacaoFolha;
    }
    
    static public function getSituacoesEntreHomologadoPago()
    {
        return [
            'Enviada para o fundo' => self::ENVIADA_FUNDO,
            'Ordem bancária emitida' => self::ORDEM_BANCARIA_EMITIDA,
        ];
    }
}

