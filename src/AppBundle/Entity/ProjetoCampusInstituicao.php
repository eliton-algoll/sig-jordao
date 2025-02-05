<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProjetoCampusInstituicao
 *
 * @ORM\Table(name="DBPET.RL_PROJETO_CAMPUSINTITUICAO")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProjetoCampusInstituicaoRepository")
 */
class ProjetoCampusInstituicao extends AbstractEntity
{
    use \AppBundle\Traits\DeleteLogicoTrait;
    use \AppBundle\Traits\DataInclusaoTrait;    
    
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\Column(name="CO_SEQ_PROJETO_CAMPUSINST", type="integer")
     * @ORM\SequenceGenerator(sequenceName="DBPET.SQ_PROJCAMPINS_COSEQPRJCAMPINS", allocationSize=1, initialValue=1)
     */
    private $coSeqProjetoCampusinst;

    /**
     * @var Projeto
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Projeto", inversedBy="campus")
     * @ORM\JoinColumn(name="CO_PROJETO", referencedColumnName="CO_SEQ_PROJETO")
     */
    private $projeto;

    /**
     * @var CampusInstituicao
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\CampusInstituicao")
     * @ORM\JoinColumn(name="CO_CAMPUS_INSTITUICAO", referencedColumnName="CO_SEQ_CAMPUS_INSTITUICAO")
     */
    private $campus;

    /**
     * @param Projeto $projeto
     * @param CampusInstituicao $campus
     */
    public function __construct(Projeto $projeto, CampusInstituicao $campus)
    {
        $this->projeto = $projeto;
        $this->campus = $campus;
        $this->stRegistroAtivo = 'S';
        $this->dtInclusao = new \DateTime();
    }

    /**
     * Get coSeqProjetoCampusinst
     *
     * @return int
     */
    public function getCoSeqProjetoCampusinst()
    {
        return $this->coSeqProjetoCampusinst;
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
     * Get campus
     *
     * @return CampusInstituicao
     */
    public function getCampus()
    {
        return $this->campus;
    }
}
