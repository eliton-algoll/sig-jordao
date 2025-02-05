<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="DBPET.RL_PERFIL_FORMAVALIACAOATIVID")
 */
class PerfilFormularioAvaliacaoAtividade extends AbstractEntity
{
    use \AppBundle\Traits\DataInclusaoTrait;
    use \AppBundle\Traits\DeleteLogicoTrait;
    
    /**
     *
     * @var integer
     * 
     * @ORM\Id
     * @ORM\Column(name="CO_SEQ_PERFIL_FORMAVALATIVID", type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="DBPET.SQ_PERFFORAVL_COSEQPERFFORAVL", allocationSize=1, initialValue=1)
     */
    private $coSeqPerfilFormavalativid;
    
    /**
     *
     * @var Perfil
     * 
     * @ORM\ManyToOne(targetEntity="\AppBundle\Entity\Perfil")
     * @ORM\JoinColumn(name="CO_PERFIL", referencedColumnName="CO_SEQ_PERFIL", nullable=false)
     */
    private $perfil;
    
    /**
     *
     * @var FormularioAvaliacaoAtividade
     * 
     * @ORM\ManyToOne(targetEntity="\AppBundle\Entity\FormularioAvaliacaoAtividade")
     * @ORM\JoinColumn(name="CO_FORM_AVALIACAO_ATIVD", referencedColumnName="CO_SEQ_FORM_AVALIACAO_ATIVD", nullable=false)
     */
    private $formularioAvaliacaoAtividade;
    
    /**
     * 
     * @param \AppBundle\Entity\Perfil $perfil
     * @param \AppBundle\Entity\FormularioAvaliacaoAtividade $formularioAvaliacaoAtividade
     */
    public function __construct(Perfil $perfil, FormularioAvaliacaoAtividade $formularioAvaliacaoAtividade)
    {
        $this->perfil = $perfil;
        $this->formularioAvaliacaoAtividade = $formularioAvaliacaoAtividade;
        $this->dtInclusao = new \DateTime();
        $this->stRegistroAtivo = 'S';
    }

    /**
     * 
     * @return integer
     */
    public function getCoSeqPerfilFormavalativid()
    {
        return $this->coSeqPerfilFormavalativid;
    }

    /**
     * 
     * @return Perfil
     */
    public function getPerfil()
    {
        return $this->perfil;
    }

    /**
     * 
     * @return FormularioAvaliacaoAtividade
     */
    public function getFormularioAvaliacaoAtividade()
    {
        return $this->formularioAvaliacaoAtividade;
    }
}
