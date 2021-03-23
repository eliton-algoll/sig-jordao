<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Integracao
 *
 * @ORM\Table(name="DBFAF.TB_INTEGRACAO")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\IntegracaoRepository")

 */
class Integracao extends AbstractEntity
{
    CONST CO_PROGRAMA_FUNDO = 61875; // código identificador do Programa Redes e ProPet
    
    /**
     * @var int
     * 
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\Column(name="CO_SEQ_INTEGRACAO", type="integer", unique=true)
     * @ORM\SequenceGenerator(sequenceName="DBFAF.SQ_INTEGRACAO_COSEQINTEGRACAO", allocationSize=1, initialValue=1)
     */
    private $coSeqIntegracao;

    /**
     * @var string
     *
     * @ORM\Column(name="NU_PROCESSO", type="string", length=17, unique=true)
     */
    private $nuProcesso;

    /**
     * @var string
     *
     * @ORM\Column(name="NU_ANO_COMPETENCIA", type="string", length=4)
     */
    private $nuAnoCompetencia;

    /**
     * @var string
     *
     * @ORM\Column(name="NU_MES_COMPETENCIA", type="string", length=2)
     */
    private $nuMesCompetencia;

    /**
     * @var string
     *
     * @ORM\Column(name="NU_CPF_CNPJ_PESSOA", type="string", length=14, nullable=true)
     */
    private $nuCpfCnpjPessoa;

    /**
     * @var int
     *
     * @ORM\Column(name="CO_PROGRAMA_FUNDO", type="integer")
     */
    private $coProgramaFundo;

    /**
     * @var int
     *
     * @ORM\Column(name="VL_BRUTO", type="integer")
     */
    private $vlBruto;

    /**
     * @var int
     *
     * @ORM\Column(name="VL_LIQUIDO", type="integer", nullable=true)
     */
    private $vlLiquido;

    /**
     * @var int
     *
     * @ORM\Column(name="VL_DESCONTO", type="integer", nullable=true)
     */
    private $vlDesconto;

    /**
     * @var int
     *
     * @ORM\Column(name="VL_EMPENHO", type="integer", nullable=true)
     */
    private $vlEmpenho;

    /**
     * @var string
     *
     * @ORM\Column(name="ST_IMPORTADO", type="string", length=1)
     */
    private $stImportado;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DT_IMPORTACAO", type="datetime", nullable=true)
     */
    private $dtImportacao;

    /**
     * @var string
     *
     * @ORM\Column(name="CO_SISTEMA_EXTERNO", type="string", length=20, nullable=true)
     */
    private $coSistemaExterno;

    /**
     * @var string
     *
     * @ORM\Column(name="NU_PARCELA", type="string", length=2, nullable=true)
     */
    private $nuParcela;

    /**
     * @var string
     *
     * @ORM\Column(name="CO_TIPO_DESCONTO", type="string", length=20, nullable=true)
     */
    private $coTipoDesconto;

    /**
     * @var string
     *
     * @ORM\Column(name="NU_CNPJ_DESCONTO", type="string", length=14, nullable=true)
     */
    private $nuCnpjDesconto;

    /**
     * @var string
     *
     * @ORM\Column(name="CO_BANCO", type="string", length=3, nullable=true)
     */
    private $coBanco;

    /**
     * @var string
     *
     * @ORM\Column(name="CO_AGENCIA_BANCARIA", type="string", length=6, nullable=true)
     */
    private $coAgenciaBancaria;

    /**
     * @var string
     *
     * @ORM\Column(name="NU_PROCESSO_JUDICIAL", type="string", length=25, nullable=true)
     */
    private $nuProcessoJudicial;

    /**
     * @var string
     *
     * @ORM\Column(name="TP_CATEGORIA", type="string", length=2)
     */
    private $tpCategoria;

    /**
     * @var string
     *
     * @ORM\Column(name="CO_MUNICIPIO_IBGE", type="string", length=6, nullable=true)
     */
    private $coMunicipioIbge;

    /**
     * @var string
     *
     * @ORM\Column(name="TP_REPASSE", type="string", length=1)
     */
    private $tpRepasse;

    /**
     * @var string
     *
     * @ORM\Column(name="DS_VARA_EXECUCAO", type="string", length=20, nullable=true)
     */
    private $dsVaraExecucao;

    /**
     * @var string
     *
     * @ORM\Column(name="SG_UF_VARA", type="string", length=2, nullable=true)
     */
    private $sgUfVara;

    /**
     * @var string
     *
     * @ORM\Column(name="DS_ACAO_CLASSE", type="string", length=10, nullable=true)
     */
    private $dsAcaoClasse;

    /**
     * @var string
     *
     * @ORM\Column(name="NU_PORTARIA", type="string", length=10, nullable=true)
     */
    private $nuPortaria;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DT_PORTARIA", type="datetime", nullable=true)
     */
    private $dtPortaria;

    /**
     * @var string
     *
     * @ORM\Column(name="NU_ANO_PARCELA", type="string", length=4, nullable=true)
     */
    private $nuAnoParcela;

    /**
     * @var int
     *
     * @ORM\Column(name="CO_NE", type="integer", nullable=true)
     */
    private $coNe;

    /**
     * @var int
     *
     * @ORM\Column(name="VL_INDICE_PRINCIPAL", type="integer", nullable=true)
     */
    private $vlIndicePrincipal;

    /**
     * @var int
     *
     * @ORM\Column(name="VL_INDICE_IMPOSTO", type="integer", nullable=true)
     */
    private $vlIndiceImposto;

    /**
     * @param string $nuProcesso
     * @param string $nuAnoCompetencia
     * @param string $nuMesCompetencia
     * @param string $nuCpfCnpjPessoa
     * @param integer $vlBruto
     * @param string $coBanco
     * @param string $coAgenciaBancaria
     * @param integer $coProgramaFundo
     * @param string $stImportado
     * @param string $tpCategoria
     * @param string $tpRepasse
     */
    public function __construct(
        $nuProcesso,
        $nuAnoCompetencia,
        $nuMesCompetencia,
        $nuCpfCnpjPessoa,
        $vlBruto,
        $coBanco,
        $coAgenciaBancaria,
        $coProgramaFundo = self::CO_PROGRAMA_FUNDO,
        $stImportado = 'N',
        $tpCategoria = '0',
        $tpRepasse = 'C'
    )
    {
        $this->nuProcesso        = $nuProcesso;
        $this->nuAnoCompetencia  = $nuAnoCompetencia;
        $this->nuMesCompetencia  = $nuMesCompetencia;
        $this->nuCpfCnpjPessoa   = $nuCpfCnpjPessoa;
        $this->vlBruto           = $vlBruto;
        $this->coBanco           = $coBanco;
        $this->coAgenciaBancaria = $coAgenciaBancaria;
        $this->coProgramaFundo   = $coProgramaFundo;
        $this->stImportado       = $stImportado;
        $this->tpCategoria       = $tpCategoria;
        $this->tpRepasse         = $tpRepasse;
    }
    
    /**
     * Set coSeqIntegracao
     *
     * @param integer $coSeqIntegracao
     *
     * @return Integracao
     */
    public function setCoSeqIntegracao($coSeqIntegracao)
    {
        $this->coSeqIntegracao = $coSeqIntegracao;

        return $this;
    }

    /**
     * Get coSeqIntegracao
     *
     * @return int
     */
    public function getCoSeqIntegracao()
    {
        return $this->coSeqIntegracao;
    }

    /**
     * Set nuProcesso
     *
     * @param string $nuProcesso
     *
     * @return Integracao
     */
    public function setNuProcesso($nuProcesso)
    {
        $this->nuProcesso = $nuProcesso;

        return $this;
    }

    /**
     * Get nuProcesso
     *
     * @return string
     */
    public function getNuProcesso()
    {
        return $this->nuProcesso;
    }

    /**
     * Set nuAnoCompetencia
     *
     * @param string $nuAnoCompetencia
     *
     * @return Integracao
     */
    public function setNuAnoCompetencia($nuAnoCompetencia)
    {
        $this->nuAnoCompetencia = $nuAnoCompetencia;

        return $this;
    }

    /**
     * Get nuAnoCompetencia
     *
     * @return string
     */
    public function getNuAnoCompetencia()
    {
        return $this->nuAnoCompetencia;
    }

    /**
     * Set nuMesCompetencia
     *
     * @param string $nuMesCompetencia
     *
     * @return Integracao
     */
    public function setNuMesCompetencia($nuMesCompetencia)
    {
        $this->nuMesCompetencia = $nuMesCompetencia;

        return $this;
    }

    /**
     * Get nuMesCompetencia
     *
     * @return string
     */
    public function getNuMesCompetencia()
    {
        return $this->nuMesCompetencia;
    }

    /**
     * Set nuCpfCnpjPessoa
     *
     * @param string $nuCpfCnpjPessoa
     *
     * @return Integracao
     */
    public function setNuCpfCnpjPessoa($nuCpfCnpjPessoa)
    {
        $this->nuCpfCnpjPessoa = $nuCpfCnpjPessoa;

        return $this;
    }

    /**
     * Get nuCpfCnpjPessoa
     *
     * @return string
     */
    public function getNuCpfCnpjPessoa()
    {
        return $this->nuCpfCnpjPessoa;
    }

    /**
     * Set coProgramaFundo
     *
     * @param integer $coProgramaFundo
     *
     * @return Integracao
     */
    public function setCoProgramaFundo($coProgramaFundo)
    {
        $this->coProgramaFundo = $coProgramaFundo;

        return $this;
    }

    /**
     * Get coProgramaFundo
     *
     * @return int
     */
    public function getCoProgramaFundo()
    {
        return $this->coProgramaFundo;
    }

    /**
     * Set vlBruto
     *
     * @param integer $vlBruto
     *
     * @return Integracao
     */
    public function setVlBruto($vlBruto)
    {
        $this->vlBruto = $vlBruto;

        return $this;
    }

    /**
     * Get vlBruto
     *
     * @return int
     */
    public function getVlBruto()
    {
        return $this->vlBruto;
    }

    /**
     * Set vlLiquido
     *
     * @param integer $vlLiquido
     *
     * @return Integracao
     */
    public function setVlLiquido($vlLiquido)
    {
        $this->vlLiquido = $vlLiquido;

        return $this;
    }

    /**
     * Get vlLiquido
     *
     * @return int
     */
    public function getVlLiquido()
    {
        return $this->vlLiquido;
    }

    /**
     * Set vlDesconto
     *
     * @param integer $vlDesconto
     *
     * @return Integracao
     */
    public function setVlDesconto($vlDesconto)
    {
        $this->vlDesconto = $vlDesconto;

        return $this;
    }

    /**
     * Get vlDesconto
     *
     * @return int
     */
    public function getVlDesconto()
    {
        return $this->vlDesconto;
    }

    /**
     * Set vlEmpenho
     *
     * @param integer $vlEmpenho
     *
     * @return Integracao
     */
    public function setVlEmpenho($vlEmpenho)
    {
        $this->vlEmpenho = $vlEmpenho;

        return $this;
    }

    /**
     * Get vlEmpenho
     *
     * @return int
     */
    public function getVlEmpenho()
    {
        return $this->vlEmpenho;
    }

    /**
     * Set stImportado
     *
     * @param string $stImportado
     *
     * @return Integracao
     */
    public function setStImportado($stImportado)
    {
        $this->stImportado = $stImportado;

        return $this;
    }

    /**
     * Get stImportado
     *
     * @return string
     */
    public function getStImportado()
    {
        return $this->stImportado;
    }

    /**
     * Set dtImportacao
     *
     * @param \DateTime $dtImportacao
     *
     * @return Integracao
     */
    public function setDtImportacao($dtImportacao)
    {
        $this->dtImportacao = $dtImportacao;

        return $this;
    }

    /**
     * Get dtImportacao
     *
     * @return \DateTime
     */
    public function getDtImportacao()
    {
        return $this->dtImportacao;
    }

    /**
     * Set coSistemaExterno
     *
     * @param string $coSistemaExterno
     *
     * @return Integracao
     */
    public function setCoSistemaExterno($coSistemaExterno)
    {
        $this->coSistemaExterno = $coSistemaExterno;

        return $this;
    }

    /**
     * Get coSistemaExterno
     *
     * @return string
     */
    public function getCoSistemaExterno()
    {
        return $this->coSistemaExterno;
    }

    /**
     * Set nuParcela
     *
     * @param string $nuParcela
     *
     * @return Integracao
     */
    public function setNuParcela($nuParcela)
    {
        $this->nuParcela = $nuParcela;

        return $this;
    }

    /**
     * Get nuParcela
     *
     * @return string
     */
    public function getNuParcela()
    {
        return $this->nuParcela;
    }

    /**
     * Set coTipoDesconto
     *
     * @param string $coTipoDesconto
     *
     * @return Integracao
     */
    public function setCoTipoDesconto($coTipoDesconto)
    {
        $this->coTipoDesconto = $coTipoDesconto;

        return $this;
    }

    /**
     * Get coTipoDesconto
     *
     * @return string
     */
    public function getCoTipoDesconto()
    {
        return $this->coTipoDesconto;
    }

    /**
     * Set nuCnpjDesconto
     *
     * @param string $nuCnpjDesconto
     *
     * @return Integracao
     */
    public function setNuCnpjDesconto($nuCnpjDesconto)
    {
        $this->nuCnpjDesconto = $nuCnpjDesconto;

        return $this;
    }

    /**
     * Get nuCnpjDesconto
     *
     * @return string
     */
    public function getNuCnpjDesconto()
    {
        return $this->nuCnpjDesconto;
    }

    /**
     * Set coBanco
     *
     * @param string $coBanco
     *
     * @return Integracao
     */
    public function setCoBanco($coBanco)
    {
        $this->coBanco = $coBanco;

        return $this;
    }

    /**
     * Get coBanco
     *
     * @return string
     */
    public function getCoBanco()
    {
        return $this->coBanco;
    }

    /**
     * Set coAgenciaBancaria
     *
     * @param string $coAgenciaBancaria
     *
     * @return Integracao
     */
    public function setCoAgenciaBancaria($coAgenciaBancaria)
    {
        $this->coAgenciaBancaria = $coAgenciaBancaria;

        return $this;
    }

    /**
     * Get coAgenciaBancaria
     *
     * @return string
     */
    public function getCoAgenciaBancaria()
    {
        return $this->coAgenciaBancaria;
    }

    /**
     * Set nuProcessoJudicial
     *
     * @param string $nuProcessoJudicial
     *
     * @return Integracao
     */
    public function setNuProcessoJudicial($nuProcessoJudicial)
    {
        $this->nuProcessoJudicial = $nuProcessoJudicial;

        return $this;
    }

    /**
     * Get nuProcessoJudicial
     *
     * @return string
     */
    public function getNuProcessoJudicial()
    {
        return $this->nuProcessoJudicial;
    }

    /**
     * Set tpCategoria
     *
     * @param string $tpCategoria
     *
     * @return Integracao
     */
    public function setTpCategoria($tpCategoria)
    {
        $this->tpCategoria = $tpCategoria;

        return $this;
    }

    /**
     * Get tpCategoria
     *
     * @return string
     */
    public function getTpCategoria()
    {
        return $this->tpCategoria;
    }

    /**
     * Set coMunicipioIbge
     *
     * @param string $coMunicipioIbge
     *
     * @return Integracao
     */
    public function setCoMunicipioIbge($coMunicipioIbge)
    {
        $this->coMunicipioIbge = $coMunicipioIbge;

        return $this;
    }

    /**
     * Get coMunicipioIbge
     *
     * @return string
     */
    public function getCoMunicipioIbge()
    {
        return $this->coMunicipioIbge;
    }

    /**
     * Set tpRepasse
     *
     * @param string $tpRepasse
     *
     * @return Integracao
     */
    public function setTpRepasse($tpRepasse)
    {
        $this->tpRepasse = $tpRepasse;

        return $this;
    }

    /**
     * Get tpRepasse
     *
     * @return string
     */
    public function getTpRepasse()
    {
        return $this->tpRepasse;
    }

    /**
     * Set dsVaraExecucao
     *
     * @param string $dsVaraExecucao
     *
     * @return Integracao
     */
    public function setDsVaraExecucao($dsVaraExecucao)
    {
        $this->dsVaraExecucao = $dsVaraExecucao;

        return $this;
    }

    /**
     * Get dsVaraExecucao
     *
     * @return string
     */
    public function getDsVaraExecucao()
    {
        return $this->dsVaraExecucao;
    }

    /**
     * Set sgUfVara
     *
     * @param string $sgUfVara
     *
     * @return Integracao
     */
    public function setSgUfVara($sgUfVara)
    {
        $this->sgUfVara = $sgUfVara;

        return $this;
    }

    /**
     * Get sgUfVara
     *
     * @return string
     */
    public function getSgUfVara()
    {
        return $this->sgUfVara;
    }

    /**
     * Set dsAcaoClasse
     *
     * @param string $dsAcaoClasse
     *
     * @return Integracao
     */
    public function setDsAcaoClasse($dsAcaoClasse)
    {
        $this->dsAcaoClasse = $dsAcaoClasse;

        return $this;
    }

    /**
     * Get dsAcaoClasse
     *
     * @return string
     */
    public function getDsAcaoClasse()
    {
        return $this->dsAcaoClasse;
    }

    /**
     * Set nuPortaria
     *
     * @param string $nuPortaria
     *
     * @return Integracao
     */
    public function setNuPortaria($nuPortaria)
    {
        $this->nuPortaria = $nuPortaria;

        return $this;
    }

    /**
     * Get nuPortaria
     *
     * @return string
     */
    public function getNuPortaria()
    {
        return $this->nuPortaria;
    }

    /**
     * Set dtPortaria
     *
     * @param \DateTime $dtPortaria
     *
     * @return Integracao
     */
    public function setDtPortaria($dtPortaria)
    {
        $this->dtPortaria = $dtPortaria;

        return $this;
    }

    /**
     * Get dtPortaria
     *
     * @return \DateTime
     */
    public function getDtPortaria()
    {
        return $this->dtPortaria;
    }

    /**
     * Set nuAnoParcela
     *
     * @param string $nuAnoParcela
     *
     * @return Integracao
     */
    public function setNuAnoParcela($nuAnoParcela)
    {
        $this->nuAnoParcela = $nuAnoParcela;

        return $this;
    }

    /**
     * Get nuAnoParcela
     *
     * @return string
     */
    public function getNuAnoParcela()
    {
        return $this->nuAnoParcela;
    }

    /**
     * Set coNe
     *
     * @param integer $coNe
     *
     * @return Integracao
     */
    public function setCoNe($coNe)
    {
        $this->coNe = $coNe;

        return $this;
    }

    /**
     * Get coNe
     *
     * @return int
     */
    public function getCoNe()
    {
        return $this->coNe;
    }

    /**
     * Set vlIndicePrincipal
     *
     * @param integer $vlIndicePrincipal
     *
     * @return Integracao
     */
    public function setVlIndicePrincipal($vlIndicePrincipal)
    {
        $this->vlIndicePrincipal = $vlIndicePrincipal;

        return $this;
    }

    /**
     * Get vlIndicePrincipal
     *
     * @return int
     */
    public function getVlIndicePrincipal()
    {
        return $this->vlIndicePrincipal;
    }

    /**
     * Set vlIndiceImposto
     *
     * @param integer $vlIndiceImposto
     *
     * @return Integracao
     */
    public function setVlIndiceImposto($vlIndiceImposto)
    {
        $this->vlIndiceImposto = $vlIndiceImposto;

        return $this;
    }

    /**
     * Get vlIndiceImposto
     *
     * @return int
     */
    public function getVlIndiceImposto()
    {
        return $this->vlIndiceImposto;
    }
}

