<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VwFolhaPagamento
 *
 * @ORM\Table(name="DBPETINFOSD.VW_FOLHA_PAGAMENTO")
 * @ORM\Entity(repositoryClass="App\Repository\VwFolhaPagamentoRepository")
 */
class VwFolhaPagamento extends AbstractEntity
{
    /**
     * @var int
     * 
     * @ORM\Column(name="CO_SEQ_FOLHA_PAGAMENTO", type="integer", nullable=true)
     */
    private $coSeqFolhaPagamento;

    /**
     * @var int
     *
     * @ORM\Column(name="CO_SEQ_PROJETO", type="integer", nullable=true)
     */
    private $coSeqProjeto;

    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(name="CO_SEQ_PROJETO_PESSOA", type="integer", nullable=true)
     */
    private $coSeqProjetoPessoa;

    /**
     * @var string
     *
     * @ORM\Column(name="NO_PESSOA", type="string", length=150, nullable=true)
     */
    private $noPessoa;

    /**
     * @var string
     *
     * @ORM\Column(name="NU_CPF", type="string", length=11, nullable=true)
     */
    private $nuCpf;

    /**
     * @var int
     *
     * @ORM\Column(name="VL_BOLSA", type="integer", nullable=true)
     */
    private $vlBolsa;

    /**
     * @var string
     *
     * @ORM\Column(name="ST_PARECER_AUTORIZA_FOLHA", type="string", length=1, nullable=true)
     */
    private $stParecerAutorizaFolha;

    /**
     * @var string
     *
     * @ORM\Column(name="NU_SIPAR", type="string", length=20, nullable=true)
     */
    private $nuSipar;

    /**
     * @var string
     *
     * @ORM\Column(name="NU_CPF_COORD", type="string", length=14, nullable=true)
     */
    private $nuCpfCoord;

    /**
     * @var string
     *
     * @ORM\Column(name="NO_COORD", type="string", length=150, nullable=true)
     */
    private $noCoord;

    /**
     * @var string
     * 
     * @ORM\Id
     * @ORM\Column(name="NU_MES", type="string", length=2)
     */
    private $nuMes;

    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(name="NU_ANO", type="string", length=4)
     */
    private $nuAno;

    /**
     * @var string
     *
     * @ORM\Column(name="ST_PARECER_PROJ_FOLHAPGTO", type="string", length=1, nullable=true)
     */
    private $stParecerProjFolhapgto;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DT_AUTORIZACAO", type="datetime", nullable=true)
     */
    private $dtAutorizacao;

    /**
     * @var int
     *
     * @ORM\Column(name="CO_SEQ_SITUACAO_PROJ_FOLHA", type="integer", nullable=true)
     */
    private $coSeqSituacaoProjFolha;

    /**
     * @var string
     *
     * @ORM\Column(name="DS_SITUACAO_PROJETO_FOLHA", type="string", length=100, nullable=true)
     */
    private $dsSituacaoProjetoFolha;

    /**
     * @var int
     *
     * @ORM\Column(name="CO_SEQ_PERFIL", type="integer", nullable=true)
     */
    private $coSeqPerfil;

    /**
     * @var string
     *
     * @ORM\Column(name="DS_PERFIL", type="string", length=30, nullable=true)
     */
    private $dsPerfil;

    /**
     * @var string
     *
     * @ORM\Column(name="ST_VOLUNTARIO_PROJETO", type="string", length=1, nullable=true)
     */
    private $stVoluntarioProjeto;

    /**
     * @var int
     *
     * @ORM\Column(name="CO_SEQ_SITUACAO_FOLHA", type="integer", nullable=true)
     */
    private $coSeqSituacaoFolha;

    /**
     * @var string
     *
     * @ORM\Column(name="DS_SITUACAO_FOLHA", type="string", length=100, nullable=true)
     */
    private $dsSituacaoFolha;

    /**
     * @var string
     *
     * @ORM\Column(name="SECRETARIA_SAUDE", type="text", nullable=true)
     */
    private $secretariaSaude;

    /**
     * @var string
     *
     * @ORM\Column(name="NO_INSTITUICAO_PROJETO", type="text", nullable=true)
     */
    private $noInstituicaoProjeto;

    /**
     * Set coSeqFolhaPagamento
     *
     * @param integer $coSeqFolhaPagamento
     *
     * @return VwFolhaPagamento
     */
    public function setCoSeqFolhaPagamento($coSeqFolhaPagamento)
    {
    	$this->coSeqFolhaPagamento = $coSeqFolhaPagamento;
    	
    	return $this;
    }
    
    /**
     * Get coSeqFolhaPagamento
     *
     * @return int
     */
    public function getCoSeqFolhaPagamento()
    {
    	return $this->coSeqFolhaPagamento;
    }
    
    /**
     * Set coSeqProjeto
     *
     * @param integer $coSeqProjeto
     *
     * @return VwFolhaPagamento
     */
    public function setCoSeqProjeto($coSeqProjeto)
    {
        $this->coSeqProjeto = $coSeqProjeto;

        return $this;
    }

    /**
     * Get coSeqProjeto
     *
     * @return int
     */
    public function getCoSeqProjeto()
    {
        return $this->coSeqProjeto;
    }

    /**
     * Set coSeqProjetoPessoa
     *
     * @param integer $coSeqProjetoPessoa
     *
     * @return VwFolhaPagamento
     */
    public function setCoSeqProjetoPessoa($coSeqProjetoPessoa)
    {
        $this->coSeqProjetoPessoa = $coSeqProjetoPessoa;

        return $this;
    }

    /**
     * Get coSeqProjetoPessoa
     *
     * @return int
     */
    public function getCoSeqProjetoPessoa()
    {
        return $this->coSeqProjetoPessoa;
    }

    /**
     * Set noPessoa
     *
     * @param string $noPessoa
     *
     * @return VwFolhaPagamento
     */
    public function setNoPessoa($noPessoa)
    {
        $this->noPessoa = $noPessoa;

        return $this;
    }

    /**
     * Get noPessoa
     *
     * @return string
     */
    public function getNoPessoa()
    {
        return $this->noPessoa;
    }

    /**
     * Set nuCpf
     *
     * @param string $nuCpf
     *
     * @return VwFolhaPagamento
     */
    public function setNuCpf($nuCpf)
    {
        $this->nuCpf = $nuCpf;

        return $this;
    }

    /**
     * Get nuCpf
     *
     * @return string
     */
    public function getNuCpf()
    {
        return $this->nuCpf;
    }

    /**
     * Set vlBolsa
     *
     * @param integer $vlBolsa
     *
     * @return VwFolhaPagamento
     */
    public function setVlBolsa($vlBolsa)
    {
        $this->vlBolsa = $vlBolsa;

        return $this;
    }

    /**
     * Get vlBolsa
     *
     * @return int
     */
    public function getVlBolsa()
    {
        return $this->vlBolsa;
    }

    /**
     * Set stParecerAutorizaFolha
     *
     * @param string $stParecerAutorizaFolha
     *
     * @return VwFolhaPagamento
     */
    public function setStParecerAutorizaFolha($stParecerAutorizaFolha)
    {
        $this->stParecerAutorizaFolha = $stParecerAutorizaFolha;

        return $this;
    }

    /**
     * Get stParecerAutorizaFolha
     *
     * @return string
     */
    public function getStParecerAutorizaFolha()
    {
        return $this->stParecerAutorizaFolha;
    }

    /**
     * Set nuSipar
     *
     * @param string $nuSipar
     *
     * @return VwFolhaPagamento
     */
    public function setNuSipar($nuSipar)
    {
        $this->nuSipar = $nuSipar;

        return $this;
    }

    /**
     * Get nuSipar
     *
     * @return string
     */
    public function getNuSipar()
    {
        return $this->nuSipar;
    }

    /**
     * Set nuCpfCoord
     *
     * @param string $nuCpfCoord
     *
     * @return VwFolhaPagamento
     */
    public function setNuCpfCoord($nuCpfCoord)
    {
        $this->nuCpfCoord = $nuCpfCoord;

        return $this;
    }

    /**
     * Get nuCpfCoord
     *
     * @return string
     */
    public function getNuCpfCoord()
    {
        return $this->nuCpfCoord;
    }

    /**
     * Set noCoord
     *
     * @param string $noCoord
     *
     * @return VwFolhaPagamento
     */
    public function setNoCoord($noCoord)
    {
        $this->noCoord = $noCoord;

        return $this;
    }

    /**
     * Get noCoord
     *
     * @return string
     */
    public function getNoCoord()
    {
        return $this->noCoord;
    }

    /**
     * Set nuMes
     *
     * @param string $nuMes
     *
     * @return VwFolhaPagamento
     */
    public function setNuMes($nuMes)
    {
        $this->nuMes = $nuMes;

        return $this;
    }

    /**
     * Get nuMes
     *
     * @return string
     */
    public function getNuMes()
    {
        return $this->nuMes;
    }

    /**
     * Set nuAno
     *
     * @param string $nuAno
     *
     * @return VwFolhaPagamento
     */
    public function setNuAno($nuAno)
    {
        $this->nuAno = $nuAno;

        return $this;
    }

    /**
     * Get nuAno
     *
     * @return string
     */
    public function getNuAno()
    {
        return $this->nuAno;
    }

    /**
     * Set stParecerProjFolhapgto
     *
     * @param string $stParecerProjFolhapgto
     *
     * @return VwFolhaPagamento
     */
    public function setStParecerProjFolhapgto($stParecerProjFolhapgto)
    {
        $this->stParecerProjFolhapgto = $stParecerProjFolhapgto;

        return $this;
    }

    /**
     * Get stParecerProjFolhapgto
     *
     * @return string
     */
    public function getStParecerProjFolhapgto()
    {
        return $this->stParecerProjFolhapgto;
    }

    /**
     * Set dtAutorizacao
     *
     * @param \DateTime $dtAutorizacao
     *
     * @return VwFolhaPagamento
     */
    public function setDtAutorizacao($dtAutorizacao)
    {
        $this->dtAutorizacao = $dtAutorizacao;

        return $this;
    }

    /**
     * Get dtAutorizacao
     *
     * @return \DateTime
     */
    public function getDtAutorizacao()
    {
        return $this->dtAutorizacao;
    }

    /**
     * Set coSeqSituacaoProjFolha
     *
     * @param integer $coSeqSituacaoProjFolha
     *
     * @return VwFolhaPagamento
     */
    public function setCoSeqSituacaoProjFolha($coSeqSituacaoProjFolha)
    {
        $this->coSeqSituacaoProjFolha = $coSeqSituacaoProjFolha;

        return $this;
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
     * Set dsSituacaoProjetoFolha
     *
     * @param string $dsSituacaoProjetoFolha
     *
     * @return VwFolhaPagamento
     */
    public function setDsSituacaoProjetoFolha($dsSituacaoProjetoFolha)
    {
        $this->dsSituacaoProjetoFolha = $dsSituacaoProjetoFolha;

        return $this;
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

    /**
     * Set coSeqPerfil
     *
     * @param integer $coSeqPerfil
     *
     * @return VwFolhaPagamento
     */
    public function setCoSeqPerfil($coSeqPerfil)
    {
        $this->coSeqPerfil = $coSeqPerfil;

        return $this;
    }

    /**
     * Get coSeqPerfil
     *
     * @return int
     */
    public function getCoSeqPerfil()
    {
        return $this->coSeqPerfil;
    }

    /**
     * Set dsPerfil
     *
     * @param string $dsPerfil
     *
     * @return VwFolhaPagamento
     */
    public function setDsPerfil($dsPerfil)
    {
        $this->dsPerfil = $dsPerfil;

        return $this;
    }

    /**
     * Get dsPerfil
     *
     * @return string
     */
    public function getDsPerfil()
    {
        return $this->dsPerfil;
    }

    /**
     * Set stVoluntarioProjeto
     *
     * @param string $stVoluntarioProjeto
     *
     * @return VwFolhaPagamento
     */
    public function setStVoluntarioProjeto($stVoluntarioProjeto)
    {
        $this->stVoluntarioProjeto = $stVoluntarioProjeto;

        return $this;
    }

    /**
     * Get stVoluntarioProjeto
     *
     * @return string
     */
    public function getStVoluntarioProjeto()
    {
        return $this->stVoluntarioProjeto;
    }

    /**
     * Set coSeqSituacaoFolha
     *
     * @param integer $coSeqSituacaoFolha
     *
     * @return VwFolhaPagamento
     */
    public function setCoSeqSituacaoFolha($coSeqSituacaoFolha)
    {
        $this->coSeqSituacaoFolha = $coSeqSituacaoFolha;

        return $this;
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
     * Set dsSituacaoFolha
     *
     * @param string $dsSituacaoFolha
     *
     * @return VwFolhaPagamento
     */
    public function setDsSituacaoFolha($dsSituacaoFolha)
    {
        $this->dsSituacaoFolha = $dsSituacaoFolha;

        return $this;
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

    /**
     * Set secretariaSaude
     *
     * @param string $secretariaSaude
     *
     * @return VwFolhaPagamento
     */
    public function setSecretariaSaude($secretariaSaude)
    {
        $this->secretariaSaude = $secretariaSaude;

        return $this;
    }

    /**
     * Get secretariaSaude
     *
     * @return string
     */
    public function getSecretariaSaude()
    {
        return $this->secretariaSaude;
    }

    /**
     * Set noInstituicaoProjeto
     *
     * @param string $noInstituicaoProjeto
     *
     * @return VwFolhaPagamento
     */
    public function setNoInstituicaoProjeto($noInstituicaoProjeto)
    {
        $this->noInstituicaoProjeto = $noInstituicaoProjeto;

        return $this;
    }

    /**
     * Get noInstituicaoProjeto
     *
     * @return string
     */
    public function getNoInstituicaoProjeto()
    {
        return $this->noInstituicaoProjeto;
    }
}

