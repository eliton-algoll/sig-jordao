<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="DBPET.TB_MODELO_CERTIFICADO")
 * @ORM\Entity(repositoryClass="App\Repository\ModeloCertificadoRepository")
 */
class ModeloCertificado extends AbstractEntity
{
    
    const CERTIFICADO = 'C';
    const DECLARACAO = 'D';
    
    /**
     * @var int
     * 
     * @ORM\Id
     * @ORM\Column(name="CO_SEQ_MODELO_CERTIFICADO", type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="DBPET.SQ_MODELOCERTIFICADO_COSEQMODELOCERTIF", allocationSize=1, initialValue=1)
     */
    private $coSeqModeloCertificado;
    
    /**
     * @var Programa
     * 
     * @ORM\ManyToOne(targetEntity="App\Entity\Programa")
     * @ORM\JoinColumn(name="CO_PROGRAMA", referencedColumnName="CO_SEQ_PROGRAMA", nullable=false)
     */
    private $programa;

    /**
     * @var string
     *
     * @ORM\Column(name="NO_MODELO_CERTIFICADO", type="string", length=200, nullable=false)
     */
    private $noModeloCertificado;

    /**
     * @var string
     *
     * @ORM\Column(name="DS_MODELO_CERTIFICADO", type="string", length=4000, nullable=false)
     */
    private $dsModeloCertificado;

    /**
     * @var string
     *
     * @ORM\Column(name="NO_IMAGEM_CERTIFICADO", type="string", length=255, nullable=false)
     */
    private $noImagemCertificado;

    /**
     * @var string
     *
     * @ORM\Column(name="NO_IMAGEM_RODAPE", type="string", length=255, nullable=false)
     */
    private $noImagemRodape;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="TP_DOCUMENTO", type="string", length=1, nullable=false)
     */
    private $tpDocumento;

    /**
     * @var string
     *
     * @ORM\Column(name="ST_REGISTRO_ATIVO", type="string", length=1, nullable=false)
     */
    private $stRegistroAtivo;

    /**
     * constructor.
     */
    public function __construct() {
        $this->stRegistroAtivo = 'S';
    }

    /**
     * @return int
     */
    public function getCoSeqModeloCertificado()
    {
        return $this->coSeqModeloCertificado;
    }

    /**
     * @param int $coSeqModeloCertificado
     */
    public function setCoSeqModeloCertificado($coSeqModeloCertificado)
    {
        $this->coSeqModeloCertificado = $coSeqModeloCertificado;
    }

    /**
     * @return Programa
     */
    public function getPrograma()
    {
        return $this->programa;
    }

    /**
     * @param Programa $programa
     */
    public function setPrograma($programa)
    {
        $this->programa = $programa;
    }

    /**
     * @return string
     */
    public function getNoModeloCertificado()
    {
        return $this->noModeloCertificado;
    }

    /**
     * @param string $noModeloCertificado
     */
    public function setNoModeloCertificado($noModeloCertificado)
    {
        $this->noModeloCertificado = $noModeloCertificado;
    }

    /**
     * @return string
     */
    public function getDsModeloCertificado()
    {
        return $this->dsModeloCertificado;
    }

    /**
     * @param string $dsModeloCertificado
     */
    public function setDsModeloCertificado($dsModeloCertificado)
    {
        $this->dsModeloCertificado = $dsModeloCertificado;
    }

    /**
     * @return string
     */
    public function getNoImagemCertificado()
    {
        return $this->noImagemCertificado;
    }

    /**
     * @param string $noImagemCertificado
     */
    public function setNoImagemCertificado($noImagemCertificado)
    {
        $this->noImagemCertificado = $noImagemCertificado;
    }

    /**
     * @return string
     */
    public function getNoImagemRodape()
    {
        return $this->noImagemRodape;
    }

    /**
     * @param string $noImagemRodape
     */
    public function setNoImagemRodape($noImagemRodape)
    {
        $this->noImagemRodape = $noImagemRodape;
    }

    /**
     * @return string
     */
    public function getTpDocumento()
    {
        return $this->tpDocumento;
    }

    /**
     * @param string $tpDocumento
     */
    public function setTpDocumento($tpDocumento)
    {
        $this->tpDocumento = $tpDocumento;
    }

    /**
     * @return string
     */
    public function getStRegistroAtivo()
    {
        return $this->stRegistroAtivo;
    }

    /**
     * @param string $stRegistroAtivo
     */
    public function setStRegistroAtivo($stRegistroAtivo)
    {
        $this->stRegistroAtivo = $stRegistroAtivo;
    }

    /**
     * @return bool
     */
    public function isAtivo()
    {
        return $this->stRegistroAtivo === 'S';
    }

    /**
     * @return string
     */
    public function getNoTipoDocumento()
    {
        $key = array_search($this->tpDocumento, self::getTpDocumentos());
        return $key !== false ? $key : null;
    }

    /**
     * @return array
     */
    public static function getTpDocumentos()
    {
        return array(
            'Certificado' => self::CERTIFICADO,
            'Declaração' => self::DECLARACAO,
        );
    }
}
