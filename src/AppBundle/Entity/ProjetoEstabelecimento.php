<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProjetoEstabelecimento
 *
 * @ORM\Table(name="DBPET.TB_PROJETO_ESTABELECIMENTO")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProjetoEstabelecimentoRepository")
 */
class ProjetoEstabelecimento extends AbstractEntity
{
    use \AppBundle\Traits\DeleteLogicoTrait;
    use \AppBundle\Traits\DataInclusaoTrait;    
    
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\Column(name="CO_SEQ_PROJETO_ESTABELEC", type="integer")
     * @ORM\SequenceGenerator(sequenceName="DBPET.SQ_PROJESTAB_COSEQPROJESTABEL", allocationSize=1, initialValue=1)
     */
    private $coSeqProjetoEstabelec;

    /**
     * @var Projeto
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Projeto", inversedBy="estabelecimentos")
     * @ORM\JoinColumn(name="CO_PROJETO", referencedColumnName="CO_SEQ_PROJETO")
     */
    private $projeto;
    
    /**
     * @var string
     *
     * @ORM\Column(name="CO_CNES", type="string")
     */
    private $coCnes;

    /**
     * @param Projeto $projeto
     * @param string $coCnes
     */
    public function __construct(Projeto $projeto, $coCnes)
    {
        $this->ativar();
        $this->dtInclusao = new \DateTime();
        $this->projeto = $projeto;
        $this->coCnes = $coCnes;
    }
    
    /**
     * Get coSeqProjetoEstabelec
     *
     * @return int
     */
    public function getCoSeqProjetoEstabelec()
    {
        return $this->coSeqProjetoEstabelec;
    }

    /**
     * Get projeto
     *
     * @return Projeto
     */
    public function getProjeto()
    {
        return $this->projeto;
    }

    /**
     * Get coCnes
     *
     * @return string
     */
    public function getCoCnes()
    {
        return $this->coCnes;
    }
}

