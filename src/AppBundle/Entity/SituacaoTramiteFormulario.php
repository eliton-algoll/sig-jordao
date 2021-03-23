<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="DBPET.TB_SITUACAO_TRAMITE_FORMULARIO")
 * @ORM\Entity(repositoryClass="\AppBundle\Repository\SituacaoTramiteFormularioRepository")
 */
class SituacaoTramiteFormulario extends AbstractEntity
{
    use \AppBundle\Traits\DataInclusaoTrait;
    use \AppBundle\Traits\DeleteLogicoTrait;    
    
    const PENDENTE = 1;
    const AGUARDANDO_ANALISE = 2;
    const DEVOLVIDO = 3;
    const FINALIZADO = 4;
    
    /**
     *
     * @var integer
     * 
     * @ORM\Id
     * @ORM\Column(name="CO_SEQ_SITUACAO_TRAMITE_FORM", type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="DBPET.SQ_SITTRAMFORM_COSEQSITTRAFORM", allocationSize=1, initialValue=1)
     */ 
    private $coSeqSituacaoTramiteForm;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NO_SITUACAO_TRAMITE_FORMULARIO", type="string", length=60, nullable=false)
     */
    private $noSituacaoTramiteFormulario;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="DS_SITUACAO_TRAMITE_FORMULARIO", type="string", length=400, nullable=false)
     */
    private $dsSituacaoTramiteFormulario;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="TP_TRAMITE_FORMULARIO", type="string", length=1, nullable=false)
     */
    private $tpTramiteFormulario;
    
    /**
     * 
     * @param string $noSituacaoTramiteFormulario
     * @param string $dsSituacaoTramiteFormulario
     * @param string $tpTramiteFormulario
     */
    public function __construct($noSituacaoTramiteFormulario, $dsSituacaoTramiteFormulario, $tpTramiteFormulario)
    {
        $this->noSituacaoTramiteFormulario = $noSituacaoTramiteFormulario;
        $this->dsSituacaoTramiteFormulario = $dsSituacaoTramiteFormulario;
        $this->tpTramiteFormulario = $tpTramiteFormulario;
    }

    /**
     * 
     * @return string
     */
    public function getCoSeqSituacaoTramiteForm()
    {
        return $this->coSeqSituacaoTramiteForm;
    }

    /**
     * 
     * @return string
     */
    public function getNoSituacaoTramiteFormulario()
    {
        return $this->noSituacaoTramiteFormulario;
    }

    /**
     * 
     * @return string
     */
    public function getDsSituacaoTramiteFormulario()
    {
        return $this->dsSituacaoTramiteFormulario;
    }

    /**
     * 
     * @return string
     */
    public function getTpTramiteFormulario()
    {
        return $this->tpTramiteFormulario;
    }

    /**
     * 
     * @return boolean
     */
    public function isPendente()
    {
        return $this->getCoSeqSituacaoTramiteForm() == self::PENDENTE;
    }
    
    /**
     * 
     * @return boolean
     */
    public function isDevolvido()
    {
        return $this->getCoSeqSituacaoTramiteForm() == self::DEVOLVIDO;
    }
    
    /**
     * 
     * @return boolean
     */
    public function isAguardandoAnalise()
    {
        return $this->getCoSeqSituacaoTramiteForm() == self::AGUARDANDO_ANALISE;
    }
    
    /**
     * 
     * @return string
     */
    public function getCssClass()
    {
        switch ($this->getCoSeqSituacaoTramiteForm()) {
            case self::PENDENTE:
            case self::DEVOLVIDO:
                return 'text-danger';
            case self::AGUARDANDO_ANALISE:
                return 'text-success';
        }
    }
    
    /**
     * 
     * @return string|null
     */
    public function getDescricaoRetorno()
    {
        switch ($this->getCoSeqSituacaoTramiteForm()) {
            case self::DEVOLVIDO:
                return 'Não satisfatório';
            case self::FINALIZADO:
                return 'Satisfatório';
        }
    }
    
    /**
     * 
     * @return array
     */
    public static function getSituacoesRetorno()
    {
        return [
            self::DEVOLVIDO,
            self::FINALIZADO,
        ];
    }
    
    /**
     * 
     * @return array
     */
    public static function getSituacoesEnvio()
    {
        return [
            self::AGUARDANDO_ANALISE,
        ];
    }
}
