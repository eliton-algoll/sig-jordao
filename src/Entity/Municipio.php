<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Municipio
 *
 * @ORM\Table(name="DBGERAL.TB_MUNICIPIO")
 * @ORM\Entity(repositoryClass="App\Repository\MunicipioRepository")

 */
class Municipio extends AbstractEntity
{

    static $perfisPorVulnerabilidade = array(
        'stQuilombola' => 'Quilombola',
        'stAmazonia' => 'Amazônia',
        'stFronteira' => 'Fronteira',
        'stSeca' => 'Seca',
        'stSemiArido' => 'Semi árido',
        'stIndigena' => 'Indígena'
    );

    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(name="CO_MUNICIPIO_IBGE", type="string", length=6, nullable=false)
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $coMunicipioIbge;

    /**
     * @var string
     *
     * @ORM\Column(name="CO_REGIONAL_SAUDE", type="string", length=4, nullable=true)
     */
    private $coRegionalSaude;

    /**
     * @var string
     *
     * @ORM\Column(name="SG_UF", type="string", length=2, nullable=true)
     */
    private $sgUf;

    /**
     * @var string
     *
     * @ORM\Column(name="CO_MACRORREGIONAL", type="string", length=4, nullable=true)
     */
    private $coMacrorregional;

    /**
     * @var string
     *
     * @ORM\Column(name="CO_MESORREGIAO", type="string", length=4, nullable=true)
     */
    private $coMesorregiao;

    /**
     * @var string
     *
     * @ORM\Column(name="CO_MICRORREGIAO", type="string", length=5, nullable=true)
     */
    private $coMicrorRegiao;

    /**
     * @var Uf
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Uf", inversedBy="municipios")
     * @ORM\JoinColumn(name="CO_UF_IBGE", referencedColumnName="CO_UF_IBGE")
     */
    private $coUfIbge;

    /**
     * @var string
     *
     * @ORM\Column(name="NO_MUNICIPIO", type="string", length=60, nullable=false)
     */
    private $noMunicipio;

    /**
     * @var string
     *
     * @ORM\Column(name="SG_MUNICIPIO", type="string", length=3, nullable=true)
     */
    private $sgMunicipio;

    /**
     * @var string
     *
     * @ORM\Column(name="NU_DDD", type="string", length=4, nullable=true)
     */
    private $nuDdd;

    /**
     * @var string
     *
     * @ORM\Column(name="NU_CEP", type="string", length=8, nullable=true)
     */
    private $nuCep;

    /**
     * @var string
     *
     * @ORM\Column(name="DV_MUNICIPIO_IBGE", type="string", length=1, nullable=true)
     */
    private $dvMunicipioIbge;

    /**
     * @var string
     *
     * @ORM\Column(name="CO_MUNICIPIO_SIAFI", type="string", length=4, nullable=true)
     */
    private $coMunicipioSiafi;

    /**
     * @var string
     *
     * @ORM\Column(name="CO_MUNICIPIO_SIAPE", type="string", length=10, nullable=true)
     */
    private $coMunicipioSiape;

    /**
     * @var string
     *
     * @ORM\Column(name="CO_SINPAS", type="string", length=5, nullable=true)
     */
    private $coSinpas;

    /**
     * @var string
     *
     * @ORM\Column(name="CO_REGIAO_CORREIO", type="string", length=5, nullable=true)
     */
    private $coRegiaoCorreio;

    /**
     * @var string
     *
     * @ORM\Column(name="ST_MUNICIPIO", type="string", length=10, nullable=true)
     */
    private $stMunicipio;

    /**
     * @var string
     *
     * @ORM\Column(name="ST_AMAZONIA", type="string", length=1, nullable=true)
     */
    private $stAmazonia;

    /**
     * @var string
     *
     * @ORM\Column(name="ST_FRONTEIRA", type="string", length=1, nullable=true)
     */
    private $stFronteira;

    /**
     * @var string
     *
     * @ORM\Column(name="CO_SUCESSOR", type="string", length=6, nullable=true)
     */
    private $coSucessor;

    /**
     * @var string
     *
     * @ORM\Column(name="NO_ANTERIOR", type="string", length=60, nullable=true)
     */
    private $noAnterior;

    /**
     * @var string
     *
     * @ORM\Column(name="ST_COMUNIDADE_SOLIDARIA", type="string", length=1, nullable=true)
     */
    private $stComunidadeSolidaria;

    /**
     * @var string
     *
     * @ORM\Column(name="ST_SECA", type="string", length=1, nullable=true)
     */
    private $stSeca;

    /**
     * @var string
     *
     * @ORM\Column(name="ST_SEMI_ARIDO", type="string", length=1, nullable=true)
     */
    private $stSemiArido;

    /**
     * @var string
     *
     * @ORM\Column(name="ST_PRMI", type="string", length=1, nullable=true)
     */
    private $stPrmi;

    /**
     * @var string
     *
     * @ORM\Column(name="ST_CALAMIDADE", type="string", length=1, nullable=true)
     */
    private $stCalamidade;

    /**
     * @var string
     *
     * @ORM\Column(name="ST_INDIGENA", type="string", length=1, nullable=true)
     */
    private $stIndigena;

    /**
     * @var float
     *
     * @ORM\Column(name="NU_DISTANCIA_CAPITAL", type="float", nullable=true)
     */
    private $nuDistanciaCapital;

    /**
     * @var string
     *
     * @ORM\Column(name="ST_CAPITAL", type="string", length=1, nullable=true)
     */
    private $stCapital;

    /**
     * @var float
     *
     * @ORM\Column(name="NU_AREA", type="float", length=1, nullable=true)
     */
    private $nuArea;

    /**
     * @var string
     *
     * @ORM\Column(name="NU_LATITUDE", type="string", length=10, nullable=true)
     */
    private $nuLatitude;

    /**
     * @var string
     *
     * @ORM\Column(name="NU_LONGITUDE", type="string", length=10, nullable=true)
     */
    private $nuLongitude;

    /**
     * @var integer
     *
     * @ORM\Column(name="NU_ALTITUDE", type="integer", nullable=true)
     */
    private $nuAltitude;

    /**
     * @var string
     *
     * @ORM\Column(name="ST_REGISTRO_ATIVO", type="string", length=1, nullable=true)
     */
    private $stRegistroAtivo;

    /**
     * @var float
     *
     * @ORM\Column(name="NU_IDH", type="float", nullable=true)
     */
    private $nuIdh;

    /**
     * @var string
     *
     * @ORM\Column(name="CO_MUNICIPIO_CORREIO", type="string", length=6, nullable=true)
     */
    private $coMunicipioCorreio;

    /**
     * @var string
     *
     * @ORM\Column(name="CO_MUNICIPIO_INSS", type="string", length=5, nullable=true)
     */
    private $coMunicipioInss;

    /**
     * @var string
     *
     * @ORM\Column(name="CO_MICRORREGIONAL_SAUDE", type="string", length=5, nullable=true)
     */
    private $coMicrorregionalSaude;

    /**
     * @var string
     *
     * @ORM\Column(name="ST_PITS", type="string", length=1, nullable=true)
     */
    private $stPits;

    /**
     * @var string
     *
     * @ORM\Column(name="ST_QUILOMBOLA", type="string", length=1, nullable=true)
     */
    private $stQuilombola;

    /**
     * @var integer
     *
     * @ORM\Column(name="CO_SEQ_ANTIGO", type="integer", nullable=true)
     */
    private $coSeqAntigo;

    /**
     * @var string
     *
     * @ORM\Column(name="NO_POPULAR", type="string", length=100, nullable=true)
     */
    private $noPopular;

    /**
     * @var string
     *
     * @ORM\Column(name="CO_REGIAO_METROPOLITANA", type="string", length=4, nullable=true)
     */
    private $coRegiaoMetropolitana;

    /**
     * @var string
     *
     * @ORM\Column(name="CO_AGLOMERADO_URBANO", type="string", length=4, nullable=true)
     */
    private $coAglomeradoUrbano;

    /**
     * @var string
     *
     * @ORM\Column(name="NU_ANO_EXTINCAO", type="string", length=4, nullable=true)
     */
    private $nuAnoExtincao;

    /**
     * @var string
     *
     * @ORM\Column(name="NU_ANO_INSTALACAO", type="string", length=4, nullable=true)
     */
    private $nuAnoInstalacao;

    /**
     * @var string
     *
     * @ORM\Column(name="ST_IBGE", type="string", length=1, nullable=true)
     */
    private $stIbge;

    /**
     * @var integer
     *
     * @ORM\Column(name="CO_MUNICIPIO_SVS", type="integer", nullable=true)
     */
    private $coMunicipioSvs;

    /**
     * @var string
     *
     * @ORM\Column(name="NO_MUNICIPIO_ACENTUADO", type="string", length=60, nullable=true)
     */
    private $noMunicipioAcentuado;

    /**
     * @var string
     *
     * @ORM\Column(name="ST_RIDE", type="string", length=1, nullable=true)
     */
    private $stRide;

    /**
     * @var string
     *
     * @ORM\Column(name="CO_MUNICIPIO_CNES", type="string", length=6, nullable=true)
     */
    private $coMunicipioCnes;

    /**
     * @var string
     *
     * @ORM\Column(name="ST_SUDAM", type="string", length=1, nullable=true)
     */
    private $stSudam;

    /**
     * @var string
     *
     * @ORM\Column(name="ST_SUDENE", type="string", length=1, nullable=true)
     */
    private $stSudene;

    /**
     * @var string
     *
     * @ORM\Column(name="ST_CADSUS", type="string", length=1, nullable=true)
     */
    private $stCadsus;

    /**
     * @var string
     *
     * @ORM\Column(name="CO_MUNICIPIO_ORIGEM", type="string", length=6, nullable=true)
     */
    private $coMunicipioOrigem;

    /**
     * @var integer
     *
     * @ORM\Column(name="CO_TERRITORIO_CIDADANIA", type="integer", nullable=true)
     */
    private $coTerritorioCidadania;

    /**
     * @var string
     *
     * @ORM\Column(name="ST_PSE", type="string", length=1, nullable=true)
     */
    private $stPse;

    /**
     * @var string
     *
     * @ORM\Column(name="ST_PAN", type="string", length=1, nullable=true)
     */
    private $stPan;

    /**
     * @var string
     *
     * @ORM\Column(name="ST_PACTO_RMINAL", type="string", length=1, nullable=true)
     */
    private $stPactoRminal;

    /**
     * @var string
     *
     * @ORM\Column(name="CO_MUNICIPIO_TABNET_RJ", type="string", length=6, nullable=true)
     */
    private $coMunicipioTabnetRj;

    /**
     * @var string
     *
     * @ORM\Column(name="TP_TIPOLOGIA_PNDR", type="string", length=20, nullable=true)
     */
    private $tpTipologiaPndr;

    /**
     * @var string
     *
     * @ORM\Column(name="CO_GRUPO_PAC2", type="string", length=2, nullable=true)
     */
    private $coGrupoPac2;

    /**
     * @var string
     *
     * @ORM\Column(name="ST_CNES", type="string", length=1, nullable=true)
     */
    private $stCnes;

    /**
     * @var integer
     *
     * @ORM\Column(name="CO_REGIAO_SAUDE", type="integer", nullable=true)
     */
    private $coRegiaoSaude;

    /**
     * @var string
     *
     * @ORM\Column(name="ST_PACTO", type="string", length=1, nullable=true)
     */
    private $stPacto;

    /**
     * @var string
     *
     * @ORM\Column(name="ST_CIB_SAS", type="string", length=1, nullable=true)
     */
    private $stCibSas;

    /**
     * @return the integer
     */
    public function getCoMunicipioIbge()
    {
        return $this->coMunicipioIbge;
    }

    /**
     * @param  $coMunicipioIbge
     * @return Municipio
     */
    public function setCoMunicipioIbge($coMunicipioIbge)
    {
        $this->coMunicipioIbge = $coMunicipioIbge;
        return $this;
    }

    /**
     * @return the string
     */
    public function getCoRegionalSaude()
    {
        return $this->coRegionalSaude;
    }

    /**
     * @param string $coRegionalSaude
     * @return Municipio
     */
    public function setCoRegionalSaude($coRegionalSaude)
    {
        $this->coRegionalSaude = $coRegionalSaude;
        return $this;
    }

    /**
     * @return the string
     */
    public function getSgUf()
    {
        return $this->sgUf;
    }

    /**
     * @param string $sgUf
     * @return Municipio
     */
    public function setSgUf($sgUf)
    {
        $this->sgUf = $sgUf;
        return $this;
    }

    /**
     * @return the string
     */
    public function getCoMacrorregional()
    {
        return $this->coMacrorregional;
    }

    /**
     * @param string $coMacrorregional
     * @return Municipio
     */
    public function setCoMacrorregional($coMacrorregional)
    {
        $this->coMacrorregional = $coMacrorregional;
        return $this;
    }

    /**
     * @return the string
     */
    public function getCoMesorregiao()
    {
        return $this->coMesorregiao;
    }

    /**
     * @param string $coMesorregiao
     * @return Municipio
     */
    public function setCoMesorregiao($coMesorregiao)
    {
        $this->coMesorregiao = $coMesorregiao;
        return $this;
    }

    /**
     * @return the string
     */
    public function getCoMicrorRegiao()
    {
        return $this->coMicrorRegiao;
    }

    /**
     * @param string $coMicrorRegiao
     * @return Municipio
     */
    public function setCoMicrorRegiao($coMicrorRegiao)
    {
        $this->coMicrorRegiao = $coMicrorRegiao;
        return $this;
    }

    /**
     * @return Uf
     */
    public function getCoUfIbge()
    {
        return $this->coUfIbge;
    }

    /**
     * @param string $coUfIbge
     * @return Municipio
     */
    public function setCoUfIbge(Uf $coUfIbge)
    {
        $this->coUfIbge = $coUfIbge;
        return $this;
    }

    /**
     * @return the string
     */
    public function getNoMunicipio()
    {
        return $this->noMunicipio;
    }

    /**
     * @param string $noMunicipio
     * @return Municipio
     */
    public function setNoMunicipio($noMunicipio)
    {
        $this->noMunicipio = $noMunicipio;
        return $this;
    }

    /**
     * @return the string
     */
    public function getSgMunicipio()
    {
        return $this->sgMunicipio;
    }

    /**
     * @param string $sgMunicipio
     * @return Municipio
     */
    public function setSgMunicipio($sgMunicipio)
    {
        $this->sgMunicipio = $sgMunicipio;
        return $this;
    }

    /**
     * @return the string
     */
    public function getNuDdd()
    {
        return $this->nuDdd;
    }

    /**
     * @param string $nuDdd
     * @return Municipio
     */
    public function setNuDdd($nuDdd)
    {
        $this->nuDdd = $nuDdd;
        return $this;
    }

    /**
     * @return the string
     */
    public function getNuCep()
    {
        return $this->nuCep;
    }

    /**
     * @param string $nuCep
     * @return Municipio
     */
    public function setNuCep($nuCep)
    {
        $this->nuCep = $nuCep;
        return $this;
    }

    /**
     * @return the string
     */
    public function getDvMunicipioIbge()
    {
        return $this->dvMunicipioIbge;
    }

    /**
     * @param string $dvMunicipioIbge
     * @return Municipio
     */
    public function setDvMunicipioIbge($dvMunicipioIbge)
    {
        $this->dvMunicipioIbge = $dvMunicipioIbge;
        return $this;
    }

    /**
     * @return the string
     */
    public function getCoMunicipioSiafi()
    {
        return $this->coMunicipioSiafi;
    }

    /**
     * @param string $coMunicipioSiafi
     * @return Municipio
     */
    public function setCoMunicipioSiafi($coMunicipioSiafi)
    {
        $this->coMunicipioSiafi = $coMunicipioSiafi;
        return $this;
    }

    /**
     * @return the string
     */
    public function getCoMunicipioSiape()
    {
        return $this->coMunicipioSiape;
    }

    /**
     * @param string $coMunicipioSiape
     * @return Municipio
     */
    public function setCoMunicipioSiape($coMunicipioSiape)
    {
        $this->coMunicipioSiape = $coMunicipioSiape;
        return $this;
    }

    /**
     * @return the string
     */
    public function getCoSinpas()
    {
        return $this->coSinpas;
    }

    /**
     * @param string $coSinpas
     * @return Municipio
     */
    public function setCoSinpas($coSinpas)
    {
        $this->coSinpas = $coSinpas;
        return $this;
    }

    /**
     * @return the string
     */
    public function getCoRegiaoCorreio()
    {
        return $this->coRegiaoCorreio;
    }

    /**
     * @param string $coRegiaoCorreio
     * @return Municipio
     */
    public function setCoRegiaoCorreio($coRegiaoCorreio)
    {
        $this->coRegiaoCorreio = $coRegiaoCorreio;
        return $this;
    }

    /**
     * @return the string
     */
    public function getStMunicipio()
    {
        return $this->stMunicipio;
    }

    /**
     * @param string $stMunicipio
     * @return Municipio
     */
    public function setStMunicipio($stMunicipio)
    {
        $this->stMunicipio = $stMunicipio;
        return $this;
    }

    /**
     * @return the string
     */
    public function getStAmazonia()
    {
        return $this->stAmazonia;
    }

    /**
     * @param string $stAmazonia
     * @return Municipio
     */
    public function setStAmazonia($stAmazonia)
    {
        $this->stAmazonia = $stAmazonia;
        return $this;
    }

    /**
     * @return the string
     */
    public function getStFronteira()
    {
        return $this->stFronteira;
    }

    /**
     * @param string $stFronteira
     * @return Municipio
     */
    public function setStFronteira($stFronteira)
    {
        $this->stFronteira = $stFronteira;
        return $this;
    }

    /**
     * @return the string
     */
    public function getCoSucessor()
    {
        return $this->coSucessor;
    }

    /**
     * @param string $coSucessor
     * @return Municipio
     */
    public function setCoSucessor($coSucessor)
    {
        $this->coSucessor = $coSucessor;
        return $this;
    }

    /**
     * @return the string
     */
    public function getNoAnterior()
    {
        return $this->noAnterior;
    }

    /**
     * @param string $noAnterior
     * @return Municipio
     */
    public function setNoAnterior($noAnterior)
    {
        $this->noAnterior = $noAnterior;
        return $this;
    }

    /**
     * @return the string
     */
    public function getStComunidadeSolidaria()
    {
        return $this->stComunidadeSolidaria;
    }

    /**
     * @param string $stComunidadeSolidaria
     * @return Municipio
     */
    public function setStComunidadeSolidaria($stComunidadeSolidaria)
    {
        $this->stComunidadeSolidaria = $stComunidadeSolidaria;
        return $this;
    }

    /**
     * @return the string
     */
    public function getStSeca()
    {
        return $this->stSeca;
    }

    /**
     * @param string $stSeca
     * @return Municipio
     */
    public function setStSeca($stSeca)
    {
        $this->stSeca = $stSeca;
        return $this;
    }

    /**
     * @return the string
     */
    public function getStSemiArido()
    {
        return $this->stSemiArido;
    }

    /**
     * @param string $stSemiArido
     * @return Municipio
     */
    public function setStSemiArido($stSemiArido)
    {
        $this->stSemiArido = $stSemiArido;
        return $this;
    }

    /**
     * @return the string
     */
    public function getStPrmi()
    {
        return $this->stPrmi;
    }

    /**
     * @param string $stPrmi
     * @return Municipio
     */
    public function setStPrmi($stPrmi)
    {
        $this->stPrmi = $stPrmi;
        return $this;
    }

    /**
     * @return the string
     */
    public function getStCalamidade()
    {
        return $this->stCalamidade;
    }

    /**
     * @param string $stCalamidade
     * @return Municipio
     */
    public function setStCalamidade($stCalamidade)
    {
        $this->stCalamidade = $stCalamidade;
        return $this;
    }

    /**
     * @return the string
     */
    public function getStIndigena()
    {
        return $this->stIndigena;
    }

    /**
     * @param string $stIndigena
     * @return Municipio
     */
    public function setStIndigena($stIndigena)
    {
        $this->stIndigena = $stIndigena;
        return $this;
    }

    /**
     * Get nuDistanciaCapital
     *
     * @return float
     */
    public function getNuDistanciaCapital()
    {
        return $this->nuDistanciaCapital;
    }

    /**
     * Set $nuDistanciaCapital
     *
     * @param float $nuDistanciaCapital
     * @return Municipio
     */
    public function setNuDistanciaCapital($nuDistanciaCapital)
    {
        $this->nuDistanciaCapital = $nuDistanciaCapital;
        return $this;
    }

    /**
     * @return the string
     */
    public function getStCapital()
    {
        return $this->stCapital;
    }

    /**
     * @param string $stCapital
     * @return Municipio
     */
    public function setStCapital($stCapital)
    {
        $this->stCapital = $stCapital;
        return $this;
    }

    /**
     * Get nuArea
     *
     * @return float
     */
    public function getNuArea()
    {
        return $this->nuArea;
    }

    /**
     * Set $nuArea
     *
     * @param float $nuArea
     * @return Municipio
     */
    public function setNuArea($nuArea)
    {
        $this->nuArea = $nuArea;
        return $this;
    }

    /**
     * @return the string
     */
    public function getNuLatitude()
    {
        return $this->nuLatitude;
    }

    /**
     * @param string $nuLatitude
     * @return Municipio
     */
    public function setNuLatitude($nuLatitude)
    {
        $this->nuLatitude = $nuLatitude;
        return $this;
    }

    /**
     * @return the string
     */
    public function getNuLongitude()
    {
        return $this->nuLongitude;
    }

    /**
     * @param string $nuLongitude
     * @return Municipio
     */
    public function setNuLongitude($nuLongitude)
    {
        $this->nuLongitude = $nuLongitude;
        return $this;
    }

    /**
     * Get nuAltitude
     *
     * @return integer
     */
    public function getNuAltitude()
    {
        return $this->nuAltitude;
    }

    /**
     * Set $nuAltitude
     *
     * @param integer $nuAltitude
     * @return Municipio
     */
    public function setNuAltitude($nuAltitude)
    {
        $this->nuAltitude = $nuAltitude;
        return $this;
    }

    /**
     * @return the string
     */
    public function getStRegistroAtivo()
    {
        return $this->stRegistroAtivo;
    }

    /**
     * @param string $stRegistroAtivo
     * @return Municipio
     */
    public function setStRegistroAtivo($stRegistroAtivo)
    {
        $this->stRegistroAtivo = $stRegistroAtivo;
        return $this;
    }

    /**
     * Get nuIdh
     *
     * @return float
     */
    public function getNuIdh()
    {
        return $this->nuIdh;
    }

    /**
     * Set $nuIdh
     *
     * @param float $nuIdh
     * @return Municipio
     */
    public function setNuIdh($nuIdh)
    {
        $this->nuIdh = $nuIdh;
        return $this;
    }

    /**
     * @return the string
     */
    public function getCoMunicipioCorreio()
    {
        return $this->coMunicipioCorreio;
    }

    /**
     * @param string $coMunicipioCorreio
     * @return Municipio
     */
    public function setCoMunicipioCorreio($coMunicipioCorreio)
    {
        $this->coMunicipioCorreio = $coMunicipioCorreio;
        return $this;
    }

    /**
     * @return the string
     */
    public function getCoMunicipioInss()
    {
        return $this->coMunicipioInss;
    }

    /**
     * @param string $coMunicipioInss
     * @return Municipio
     */
    public function setCoMunicipioInss($coMunicipioInss)
    {
        $this->coMunicipioInss = $coMunicipioInss;
        return $this;
    }

    /**
     * @return the string
     */
    public function getCoMicrorregionalSaude()
    {
        return $this->coMicrorregionalSaude;
    }

    /**
     * @param string $coMicrorregionalSaude
     * @return Municipio
     */
    public function setCoMicrorregionalSaude($coMicrorregionalSaude)
    {
        $this->coMicrorregionalSaude = $coMicrorregionalSaude;
        return $this;
    }

    /**
     * @return the string
     */
    public function getStPits()
    {
        return $this->stPits;
    }

    /**
     * @param string $stPits
     * @return Municipio
     */
    public function setStPits($stPits)
    {
        $this->stPits = $stPits;
        return $this;
    }

    /**
     * @return the string
     */
    public function getStQuilombola()
    {
        return $this->stQuilombola;
    }

    /**
     * @param string $stQuilombola
     * @return Municipio
     */
    public function setStQuilombola($stQuilombola)
    {
        $this->stQuilombola = $stQuilombola;
        return $this;
    }

    /**
     * Get coSeqAntigo
     *
     * @return integer
     */
    public function getCoSeqAntigo()
    {
        return $this->coSeqAntigo;
    }

    /**
     * Set $coSeqAntigo
     *
     * @param integer $coSeqAntigo
     * @return Municipio
     */
    public function setCoSeqAntigo($coSeqAntigo)
    {
        $this->coSeqAntigo = $coSeqAntigo;
        return $this;
    }

    /**
     * @return the string
     */
    public function getNoPopular()
    {
        return $this->noPopular;
    }

    /**
     * @param string $noPopular
     * @return Municipio
     */
    public function setNoPopular($noPopular)
    {
        $this->noPopular = $noPopular;
        return $this;
    }

    /**
     * @return the string
     */
    public function getCoRegiaoMetropolitana()
    {
        return $this->coRegiaoMetropolitana;
    }

    /**
     * @param string $coRegiaoMetropolitana
     * @return Municipio
     */
    public function setCoRegiaoMetropolitana($coRegiaoMetropolitana)
    {
        $this->coRegiaoMetropolitana = $coRegiaoMetropolitana;
        return $this;
    }

    /**
     * @return the string
     */
    public function getCoAglomeradoUrbano()
    {
        return $this->coAglomeradoUrbano;
    }

    /**
     * @param string $coAglomeradoUrbano
     * @return Municipio
     */
    public function setCoAglomeradoUrbano($coAglomeradoUrbano)
    {
        $this->coAglomeradoUrbano = $coAglomeradoUrbano;
        return $this;
    }

    /**
     * @return the string
     */
    public function getNuAnoExtincao()
    {
        return $this->nuAnoExtincao;
    }

    /**
     * @param string $nuAnoExtincao
     * @return Municipio
     */
    public function setNuAnoExtincao($nuAnoExtincao)
    {
        $this->nuAnoExtincao = $nuAnoExtincao;
        return $this;
    }

    /**
     * @return the string
     */
    public function getNuAnoInstalacao()
    {
        return $this->nuAnoInstalacao;
    }

    /**
     * @param string $nuAnoInstalacao
     * @return Municipio
     */
    public function setNuAnoInstalacao($nuAnoInstalacao)
    {
        $this->nuAnoInstalacao = $nuAnoInstalacao;
        return $this;
    }

    /**
     * @return the string
     */
    public function getStIbge()
    {
        return $this->stIbge;
    }

    /**
     * @param string $stIbge
     * @return Municipio
     */
    public function setStIbge($stIbge)
    {
        $this->stIbge = $stIbge;
        return $this;
    }

    /**
     * Get coMunicipioSvs
     *
     * @return integer
     */
    public function getCoMunicipioSvs()
    {
        return $this->coMunicipioSvs;
    }

    /**
     * Set $coMunicipioSvs
     *
     * @param integer $coMunicipioSvs
     * @return Municipio
     */
    public function setCoMunicipioSvs($coMunicipioSvs)
    {
        $this->coMunicipioSvs = $coMunicipioSvs;
        return $this;
    }

    /**
     * @return the string
     */
    public function getNoMunicipioAcentuado()
    {
        return $this->noMunicipioAcentuado;
    }

    /**
     * @param string $noMunicipioAcentuado
     * @return Municipio
     */
    public function setNoMunicipioAcentuado($noMunicipioAcentuado)
    {
        $this->noMunicipioAcentuado = $noMunicipioAcentuado;
        return $this;
    }

    /**
     * @return the string
     */
    public function getStRide()
    {
        return $this->stRide;
    }

    /**
     * @param string $stRide
     * @return Municipio
     */
    public function setStRide($stRide)
    {
        $this->stRide = $stRide;
        return $this;
    }

    /**
     * @return the string
     */
    public function getCoMunicipioCnes()
    {
        return $this->coMunicipioCnes;
    }

    /**
     * @param string $coMunicipioCnes
     * @return Municipio
     */
    public function setCoMunicipioCnes($coMunicipioCnes)
    {
        $this->coMunicipioCnes = $coMunicipioCnes;
        return $this;
    }

    /**
     * @return the string
     */
    public function getStSudam()
    {
        return $this->stSudam;
    }

    /**
     * @param string $stSudam
     * @return Municipio
     */
    public function setStSudam($stSudam)
    {
        $this->stSudam = $stSudam;
        return $this;
    }

    /**
     * @return the string
     */
    public function getStSudene()
    {
        return $this->stSudene;
    }

    /**
     * @param string $stSudene
     * @return Municipio
     */
    public function setStSudene($stSudene)
    {
        $this->stSudene = $stSudene;
        return $this;
    }

    /**
     * @return the string
     */
    public function getStCadsus()
    {
        return $this->stCadsus;
    }

    /**
     * @param string $stCadsus
     * @return Municipio
     */
    public function setStCadsus($stCadsus)
    {
        $this->stCadsus = $stCadsus;
        return $this;
    }

    /**
     * @return the string
     */
    public function getCoMunicipioOrigem()
    {
        return $this->coMunicipioOrigem;
    }

    /**
     * @param string $coMunicipioOrigem
     * @return Municipio
     */
    public function setCoMunicipioOrigem($coMunicipioOrigem)
    {
        $this->coMunicipioOrigem = $coMunicipioOrigem;
        return $this;
    }

    /**
     * Get coTerritorioCidadania
     *
     * @return integer
     */
    public function getCoTerritorioCidadania()
    {
        return $this->coTerritorioCidadania;
    }

    /**
     * Set $coTerritorioCidadania
     *
     * @param integer $coTerritorioCidadania
     * @return Municipio
     */
    public function setCoTerritorioCidadania($coTerritorioCidadania)
    {
        $this->coTerritorioCidadania = $coTerritorioCidadania;
        return $this;
    }

    /**
     * @return the string
     */
    public function getStPse()
    {
        return $this->stPse;
    }

    /**
     * @param string $stPse
     * @return Municipio
     */
    public function setStPse($stPse)
    {
        $this->stPse = $stPse;
        return $this;
    }

    /**
     * @return the string
     */
    public function getStPan()
    {
        return $this->stPan;
    }

    /**
     * @param string $stPan
     * @return Municipio
     */
    public function setStPan($stPan)
    {
        $this->stPan = $stPan;
        return $this;
    }

    /**
     * @return the string
     */
    public function getStPactoRminal()
    {
        return $this->stPactoRminal;
    }

    /**
     * @param string $stPactoRminal
     * @return Municipio
     */
    public function setStPactoRminal($stPactoRminal)
    {
        $this->stPactoRminal = $stPactoRminal;
        return $this;
    }

    /**
     * @return the string
     */
    public function getCoMunicipioTabnetRj()
    {
        return $this->coMunicipioTabnetRj;
    }

    /**
     * @param string $coMunicipioTabnetRj
     * @return Municipio
     */
    public function setCoMunicipioTabnetRj($coMunicipioTabnetRj)
    {
        $this->coMunicipioTabnetRj = $coMunicipioTabnetRj;
        return $this;
    }

    /**
     * @return the string
     */
    public function getTpTipologiaPndr()
    {
        return $this->tpTipologiaPndr;
    }

    /**
     * @param string $tpTipologiaPndr
     * @return Municipio
     */
    public function setTpTipologiaPndr($tpTipologiaPndr)
    {
        $this->tpTipologiaPndr = $tpTipologiaPndr;
        return $this;
    }

    /**
     * @return the string
     */
    public function getCoGrupoPac2()
    {
        return $this->coGrupoPac2;
    }

    /**
     * @param string $coGrupoPac2
     * @return Municipio
     */
    public function setCoGrupoPac2($coGrupoPac2)
    {
        $this->coGrupoPac2 = $coGrupoPac2;
        return $this;
    }

    /**
     * @return the string
     */
    public function getStCnes()
    {
        return $this->stCnes;
    }

    /**
     * @param string $stCnes
     * @return Municipio
     */
    public function setStCnes($stCnes)
    {
        $this->stCnes = $stCnes;
        return $this;
    }

    /**
     * Get coRegiaoSaude
     *
     * @return integer
     */
    public function getCoRegiaoSaude()
    {
        return $this->coRegiaoSaude;
    }

    /**
     * Set $coRegiaoSaude
     *
     * @param integer $coRegiaoSaude
     * @return Municipio
     */
    public function setCoRegiaoSaude($coRegiaoSaude)
    {
        $this->coRegiaoSaude = $coRegiaoSaude;
        return $this;
    }

    /**
     * @return the string
     */
    public function getStPacto()
    {
        return $this->stPacto;
    }

    /**
     * @param string $stPacto
     * @return Municipio
     */
    public function setStPacto($stPacto)
    {
        $this->stPacto = $stPacto;
        return $this;
    }

    /**
     * @return the string
     */
    public function getStCibSas()
    {
        return $this->stCibSas;
    }

    /**
     * @param string $stCibSas
     * @return Municipio
     */
    public function setStCibSas($stCibSas)
    {
        $this->stCibSas = $stCibSas;
        return $this;
    }

    /**
     * retorna o nome do perfil por vulnerabilidade de acordo com o
     * campo st que estiver setado como "S".
     * 
     * Ex: se o campo stAmazonia = "S" o retorno será Amazônia.
     */
    public static function retrievePerfilPorVulnerabilidade($campo)
    {
        $perfis = self::$perfisPorVulnerabilidade;
        return $perfis[$campo];
    }

}
