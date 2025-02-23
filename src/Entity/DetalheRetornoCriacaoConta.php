<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Cpb\RetornoCadastro\Detalhe;

/**
 * @ORM\Entity
 * @ORM\Table(name="DBPETINFOSD.TB_DETALHE_RET_CRIA_CONTA")
 * @ORM\Entity(repositoryClass="App\Repository\DetalheRetornoCriacaoContaRepository")
 */
class DetalheRetornoCriacaoConta
{
    /**
     *
     * @var integer
     * 
     * @ORM\Id
     * @ORM\Column(name="CO_SEQ_DETALHE_RET_CRIA_CONTA", type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="DBPETINFOSD.SQ_DETRETCRIACON_COSEQDETRETCC", initialValue=1, allocationSize=1)
     */
    private $coSeqDetalheRetCriaConta;
    
    /**
     *
     * @var RetornoCriacaoConta
     * 
     * @ORM\ManyToOne(targetEntity="\App\Entity\RetornoCriacaoConta")
     * @ORM\JoinColumn(name="CO_RETORNO_CRIACAO_CONTA", referencedColumnName="CO_SEQ_RETORNO_CRIACAO_CONTA", nullable=false)
     */
    private $retornoCriacaoConta;    
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NU_TIPO_REGISTRO", type="string", length=1, nullable=false)
     */
    private $nuTipoRegistro;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NU_SEQUENCIAL_REGISTRO", type="string", length=7)
     */
    private $nuSequencialRegistro;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NU_TIPO_LOTE", type="string", length=2)
     */
    private $nuTipoLote;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NU_NIB", type="string", length=10)
     */
    private $nuNib;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NU_ID_ORGAO_PAGADOR", type="string", length=6)
     */
    private $nuIdOrgaoPagador;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NU_ESPECIE", type="string", length=3)
     */
    private $nuEspecie;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NU_AGENCIA_CONVENIO", type="string", length=8)
     */
    private $nuAgenciaConvenio;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NU_INDICADOR_PRESTACAO_UNICA", type="string", length=1)
     */
    private $nuIndicadorPrestacaoUnica;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NU_CONTA", type="string", length=10)
     */
    private $nuConta;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NU_CPF", type="string", length=11)
     */
    private $nuCpf;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NO_BENEFICIARIO", type="string", length=28)
     */
    private $noBeneficiario;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NU_NIT", type="string", length=11)
     */
    private $nuNit;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="DS_ENDERECO", type="string", length=40)
     */
    private $dsEndereco;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NO_BAIRRO", type="string", length=17)
     */
    private $noBairro;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NU_CEP", type="string", length=8)
     */
    private $nuCep;
    
    /**
     *
     * @var \DateTime
     * 
     * @ORM\Column(name="DT_NASCIMENTO", type="datetime")
     */
    private $dtNascimento;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NO_MAE_BENEFICIARIO", type="string", length=32)
     */
    private $noMaeBeneficiario;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NU_DIA_UTIL", type="string", length=2)
     */
    private $nuDiaUtil;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NU_TIPO_DADO_CADASTRO", type="string", length=2)
     */
    private $nuTipoDadoCadastro;
    
    /**
     *
     * @var \DateTime
     * 
     * @ORM\Column(name="DT_ULTIMA_ATUALIZACAO_ENDERECO", type="datetime")
     */
    private $dtUltimaAtualizacao;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NU_SEQUENCIAL_REMESSA", type="string", length=6)
     */
    private $nuSequencialRemessa;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NU_SITUACAO_CADASTRO", type="string", length=4)
     */
    private $nuSituacaoCadastro;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NU_SITUACAO_REMESSA", type="string", length=1)
     */
    private $nuSituacaoRemessa;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="ST_BENEFICIARIO_PROGRAMA", type="string", length=1, nullable=false)
     */
    private $stBeneficiarioPrograma;
    
    /**
     * 
     * @param RetornoCriacaoConta $retornoCriacaoConta
     * @param Detalhe $detalhe
     * @param OcorrenciaRetornoCriacaoConta $ocorrenciaRetornoCriacaoConta
     */
    public function __construct(
        RetornoCriacaoConta $retornoCriacaoConta,
        Detalhe $detalhe
    ) {
        $this->retornoCriacaoConta = $retornoCriacaoConta;
        $this->stBeneficiarioPrograma = 'S';
        $this->fromDetalhe($detalhe);        
    }
    
    /**
     * 
     * @return integer
     */
    public function getCoSeqDetalheRetCriaConta()
    {
        return $this->coSeqDetalheRetCriaConta;
    }

    /**
     * 
     * @return RetornoCriacaoConta
     */
    public function getRetornoCriacaoConta()
    {
        return $this->retornoCriacaoConta;
    }

    /**
     * 
     * @return string
     */
    public function getNuTipoRegistro()
    {
        return $this->nuTipoRegistro;
    }

    /**
     * 
     * @return string
     */
    public function getNuSequencialRegistro()
    {
        return $this->nuSequencialRegistro;
    }

    /**
     * 
     * @return string
     */
    public function getNuTipoLote()
    {
        return $this->nuTipoLote;
    }

    /**
     * 
     * @return string
     */
    public function getNuNib()
    {
        return $this->nuNib;
    }

    /**
     * 
     * @return string
     */
    public function getNuIdOrgaoPagador()
    {
        return $this->nuIdOrgaoPagador;
    }

    /**
     * 
     * @return string
     */
    public function getNuEspecie()
    {
        return $this->nuEspecie;
    }

    /**
     * 
     * @return string
     */
    public function getNuAgenciaConvenio()
    {
        return $this->nuAgenciaConvenio;
    }

    /**
     * 
     * @return string
     */
    public function getNuIndicadorPrestacaoUnica()
    {
        return $this->nuIndicadorPrestacaoUnica;
    }

    /**
     * 
     * @return string
     */
    public function getNuConta()
    {
        return $this->nuConta;
    }

    /**
     * 
     * @return string
     */
    public function getNuCpf()
    {
        return $this->nuCpf;
    }

    /**
     * 
     * @return string
     */
    public function getNoBeneficiario()
    {
        return $this->noBeneficiario;
    }

    /**
     * 
     * @return string
     */
    public function getNuNit()
    {
        return $this->nuNit;
    }

    /**
     * 
     * @return string
     */
    public function getDsEndereco()
    {
        return $this->dsEndereco;
    }

    /**
     * 
     * @return string
     */
    public function getNoBairro()
    {
        return $this->noBairro;
    }

    /**
     * 
     * @return string
     */
    public function getNuCep()
    {
        return $this->nuCep;
    }

    /**
     * 
     * @return \DateTime
     */
    public function getDtNascimento()
    {
        return $this->dtNascimento;
    }

    /**
     * 
     * @return string
     */
    public function getNoMaeBeneficiario()
    {
        return $this->noMaeBeneficiario;
    }

    /**
     * 
     * @return string
     */
    public function getNuDiaUtil()
    {
        return $this->nuDiaUtil;
    }

    /**
     * 
     * @return string
     */
    public function getNuTipoDadoCadastro()
    {
        return $this->nuTipoDadoCadastro;
    }

    /**
     * 
     * @return \DateTime
     */
    public function getDtUltimaAtualizacao()
    {
        return $this->dtUltimaAtualizacao;
    }

    /**
     * 
     * @return string
     */
    public function getNuSequencialRemessa()
    {
        return $this->nuSequencialRemessa;
    }

    /**
     * 
     * @return string
     */
    public function getNuSituacaoCadastro()
    {
        return $this->nuSituacaoCadastro;
    }

    /**
     * 
     * @return string
     */
    public function getNuSituacaoRemessa()
    {
        return $this->nuSituacaoRemessa;
    }
    
    /**
     * 
     * @return boolean
     */
    public function isBeneficiarioPrograma()
    {
        return $this->stBeneficiarioPrograma === 'S';
    }
    
    public function putAsBeneficiarioPrograma()
    {
        $this->stBeneficiarioPrograma = 'S';
    }
    
    public function putAsNaoBeneficiarioPrograma()
    {
        $this->stBeneficiarioPrograma = 'N';
    }
        
    /**
     * 
     * @param Detalhe $detalhe
     */
    private function fromDetalhe(Detalhe $detalhe)
    {
        $this->nuTipoRegistro = $detalhe->getCsTipoRegistro();
        $this->nuSequencialRegistro = $detalhe->getNuSeqRegistro();
        $this->nuTipoLote = $detalhe->getCsTipoLote();
        $this->nuNib = $detalhe->getNuNib();
        $this->nuIdOrgaoPagador = $detalhe->getIdOrgaoPagador();
        $this->nuEspecie = $detalhe->getCsEspecie();
        $this->nuAgenciaConvenio = $detalhe->getIdAgenciaConv();
        $this->nuIndicadorPrestacaoUnica = $detalhe->getInPrestacaoUnica();
        $this->nuConta = $detalhe->getNuConta();
        $this->nuCpf = $detalhe->getNuCpf();
        $this->noBeneficiario = $detalhe->getNmBeneficiario();
        $this->nuNit = $detalhe->getIdNit();
        $this->dsEndereco = $detalhe->getTeEndereco();
        $this->noBairro = $detalhe->getTeBairro();
        $this->nuCep = $detalhe->getNuCep();
        $this->dtNascimento = \DateTime::createFromFormat('Ymd', $detalhe->getDtNasc());
        $this->noMaeBeneficiario = $detalhe->getNmMae();
        $this->nuDiaUtil = $detalhe->getNuDiaUtil();
        $this->nuTipoDadoCadastro = $detalhe->getCsTipoDadoCad();        
        $this->nuSequencialRemessa = $detalhe->getNuSeqRms();
        $this->nuSituacaoCadastro = $detalhe->getSitCmd();
        $this->nuSituacaoRemessa = $detalhe->getSitRms();
        
        if ($detalhe->getDtUltAtuEnd()) {
            $this->dtUltimaAtualizacao = \DateTime::createFromFormat('Ymd', $detalhe->getDtUltAtuEnd());
        }
    }
}
