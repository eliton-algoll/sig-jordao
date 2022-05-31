<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * PessoaFisica
 *
 * @ORM\Table(name="DBPESSOA.TB_PESSOA_FISICA")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PessoaFisicaRepository")
 */
class PessoaFisica extends AbstractEntity
{
    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(name="NU_CPF", type="string", length=11, nullable=false, unique=true)
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $nuCpf;

    /**
     * @var string
     *
     * @ORM\Column(name="CO_NATUREZA_OCUPACAO", type="string", length=3, nullable=true)
     */
    private $coNaturezaOcupacao;

    /**
     * @var string
     *
     * @ORM\Column(name="CO_OCUPACAO_PRINCIPAL", type="string", length=3, nullable=true)
     */
    private $coOcupacaoPrincipal;

    /**
     * @var string
     *
     * @ORM\Column(name="CO_PAIS_EXTERIOR", type="string", length=4, nullable=true)
     */
    private $coPaisExterior;

    /**
     * @var string
     *
     * @ORM\Column(name="CO_UNIDADE_ADMINISTRATIVA", type="string", length=7, nullable=true)
     */
    private $coUnidadeAdministrativa;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DT_NASCIMENTO", type="datetime", nullable=true)
     */
    private $dtNascimento;

    /**
     * @var string
     *
     * @ORM\Column(name="NO_MAE", type="string", length=70, nullable=true)
     */
    private $noMae;

    /**
     * @var string
     *
     * @ORM\Column(name="NO_PAIS_EXTERIOR", type="string", length=60, nullable=true)
     */
    private $noPaisExterior;

    /**
     * @var string
     *
     * @ORM\Column(name="NU_ANO_EXERCICIO_NATOCUP", type="string", length=4, nullable=true)
     */
    private $nuAnoExercicioNatocup;

    /**
     * @var string
     *
     * @ORM\Column(name="NU_ANO_OBITO", type="string", length=4, nullable=true)
     */
    private $nuAnoObito;

    /**
     * @var string
     *
     * @ORM\Column(name="NU_CARTAO_SUS", type="string", length=15, nullable=true)
     */
    private $nuCartaoSus;

    /**
     * @var string
     *
     * @ORM\Column(name="NU_TITULO_ELEITOR", type="string", length=13, nullable=true)
     */
    private $nuTituloEleitor;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Sexo")
     * @ORM\JoinColumn(name="SG_SEXO", referencedColumnName="CO_SEXO")
     */
    private $sexo;

    /**
     * @var string
     *
     * @ORM\Column(name="ST_ESTRANGEIRO", type="string", length=1, nullable=true)
     */
    private $stEstrangeiro;

    /**
     * @var string
     *
     * @ORM\Column(name="ST_RESIDENTE_EXTERIOR", type="string", length=1, nullable=true)
     */
    private $stResidenteExterior;

    /**
     * @var boolean
     *
     * @ORM\Column(name="TP_SEXO", type="boolean", nullable=true)
     */
    private $tpSexo;

    /**
     * @var integer
     *
     * @ORM\Column(name="TP_SITUACAO_CPF", type="integer", nullable=true)
     */
    private $tpSituacaoCpf;

    /**
     * @var ArrayCollection<PessoaPerfil>
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\PessoaPerfil", mappedBy="pessoaFisica", cascade={"persist"})
     */
    private $pessoasPerfis;

    /**
     * @var Pessoa
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Pessoa")
     * @ORM\JoinColumn(name="NU_CPF", referencedColumnName="NU_CPF_CNPJ_PESSOA")
     */
    private $pessoa;

    /**
     * @var DadoPessoal
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\DadoPessoal", mappedBy="pessoaFisica", cascade={"persist"})
     */
    private $dadoPessoal;

    /**
     * @var Usuario
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Usuario", mappedBy="pessoaFisica")
     */
    private $usuario;

    public function __construct()
    {
        $this->pessoasPerfis = new ArrayCollection();
    }

    /**
     * Set coNaturezaOcupacao
     *
     * @param string $coNaturezaOcupacao
     * @return PessoaFisica
     */
    public function setCoNaturezaOcupacao($coNaturezaOcupacao)
    {
        $this->coNaturezaOcupacao = $coNaturezaOcupacao;

        return $this;
    }

    /**
     * Get coNaturezaOcupacao
     *
     * @return string
     */
    public function getCoNaturezaOcupacao()
    {
        return $this->coNaturezaOcupacao;
    }

    /**
     * Set coOcupacaoPrincipal
     *
     * @param string $coOcupacaoPrincipal
     * @return PessoaFisica
     */
    public function setCoOcupacaoPrincipal($coOcupacaoPrincipal)
    {
        $this->coOcupacaoPrincipal = $coOcupacaoPrincipal;

        return $this;
    }

    /**
     * Get coOcupacaoPrincipal
     *
     * @return string
     */
    public function getCoOcupacaoPrincipal()
    {
        return $this->coOcupacaoPrincipal;
    }

    /**
     * Set coPaisExterior
     *
     * @param string $coPaisExterior
     * @return PessoaFisica
     */
    public function setCoPaisExterior($coPaisExterior)
    {
        $this->coPaisExterior = $coPaisExterior;

        return $this;
    }

    /**
     * Get coPaisExterior
     *
     * @return string
     */
    public function getCoPaisExterior()
    {
        return $this->coPaisExterior;
    }

    /**
     * Set coUnidadeAdministrativa
     *
     * @param string $coUnidadeAdministrativa
     * @return PessoaFisica
     */
    public function setCoUnidadeAdministrativa($coUnidadeAdministrativa)
    {
        $this->coUnidadeAdministrativa = $coUnidadeAdministrativa;

        return $this;
    }

    /**
     * Get coUnidadeAdministrativa
     *
     * @return string
     */
    public function getCoUnidadeAdministrativa()
    {
        return $this->coUnidadeAdministrativa;
    }

    /**
     * Set dtNascimento
     *
     * @param \DateTime $dtNascimento
     * @return PessoaFisica
     */
    public function setDtNascimento($dtNascimento)
    {
        $this->dtNascimento = $dtNascimento;

        return $this;
    }

    /**
     * Get dtNascimento
     *
     * @return \DateTime
     */
    public function getDtNascimento()
    {
        return $this->dtNascimento;
    }

    /**
     * Set noMae
     *
     * @param string $noMae
     * @return PessoaFisica
     */
    public function setNoMae($noMae)
    {
        $this->noMae = $noMae;

        return $this;
    }

    /**
     * Get noMae
     *
     * @return string
     */
    public function getNoMae()
    {
        return $this->noMae;
    }

    /**
     * Set noPaisExterior
     *
     * @param string $noPaisExterior
     * @return PessoaFisica
     */
    public function setNoPaisExterior($noPaisExterior)
    {
        $this->noPaisExterior = $noPaisExterior;

        return $this;
    }

    /**
     * Get noPaisExterior
     *
     * @return string
     */
    public function getNoPaisExterior()
    {
        return $this->noPaisExterior;
    }

    /**
     * Set nuAnoExercicioNatocup
     *
     * @param string $nuAnoExercicioNatocup
     * @return PessoaFisica
     */
    public function setNuAnoExercicioNatocup($nuAnoExercicioNatocup)
    {
        $this->nuAnoExercicioNatocup = $nuAnoExercicioNatocup;

        return $this;
    }

    /**
     * Get nuAnoExercicioNatocup
     *
     * @return string
     */
    public function getNuAnoExercicioNatocup()
    {
        return $this->nuAnoExercicioNatocup;
    }

    /**
     * Set nuAnoObito
     *
     * @param string $nuAnoObito
     * @return PessoaFisica
     */
    public function setNuAnoObito($nuAnoObito)
    {
        $this->nuAnoObito = $nuAnoObito;

        return $this;
    }

    /**
     * Get nuAnoObito
     *
     * @return string
     */
    public function getNuAnoObito()
    {
        return $this->nuAnoObito;
    }

    /**
     * Set nuCartaoSus
     *
     * @param string $nuCartaoSus
     * @return PessoaFisica
     */
    public function setNuCartaoSus($nuCartaoSus)
    {
        $this->nuCartaoSus = $nuCartaoSus;

        return $this;
    }

    /**
     * Get nuCartaoSus
     *
     * @return string
     */
    public function getNuCartaoSus()
    {
        return $this->nuCartaoSus;
    }

    /**
     * Set nuTituloEleitor
     *
     * @param string $nuTituloEleitor
     * @return PessoaFisica
     */
    public function setNuTituloEleitor($nuTituloEleitor)
    {
        $this->nuTituloEleitor = $nuTituloEleitor;

        return $this;
    }

    /**
     * Get nuTituloEleitor
     *
     * @return string
     */
    public function getNuTituloEleitor()
    {
        return $this->nuTituloEleitor;
    }

    /**
     * Set sexo
     *
     * @param string $sexo
     * @return PessoaFisica
     */
    public function setSexo($sexo)
    {
        $this->sexo = $sexo;

        return $this;
    }

    /**
     * Get sexo
     *
     * @return string
     */
    public function getSexo()
    {
        return $this->sexo;
    }

    /**
     * Set stEstrangeiro
     *
     * @param string $stEstrangeiro
     * @return PessoaFisica
     */
    public function setStEstrangeiro($stEstrangeiro)
    {
        $this->stEstrangeiro = $stEstrangeiro;

        return $this;
    }

    /**
     * Get stEstrangeiro
     *
     * @return string
     */
    public function getStEstrangeiro()
    {
        return $this->stEstrangeiro;
    }

    /**
     * Set stResidenteExterior
     *
     * @param string $stResidenteExterior
     * @return PessoaFisica
     */
    public function setStResidenteExterior($stResidenteExterior)
    {
        $this->stResidenteExterior = $stResidenteExterior;

        return $this;
    }

    /**
     * Get stResidenteExterior
     *
     * @return string
     */
    public function getStResidenteExterior()
    {
        return $this->stResidenteExterior;
    }

    /**
     * Set tpSexo
     *
     * @param boolean $tpSexo
     * @return PessoaFisica
     */
    public function setTpSexo($tpSexo)
    {
        $this->tpSexo = $tpSexo;

        return $this;
    }

    /**
     * Get tpSexo
     *
     * @return boolean
     */
    public function getTpSexo()
    {
        return $this->tpSexo;
    }

    /**
     * Set tpSituacaoCpf
     *
     * @param boolean $tpSituacaoCpf
     * @return PessoaFisica
     */
    public function setTpSituacaoCpf($tpSituacaoCpf)
    {
        $this->tpSituacaoCpf = $tpSituacaoCpf;

        return $this;
    }

    /**
     * Get tpSituacaoCpf
     *
     * @return boolean
     */
    public function getTpSituacaoCpf()
    {
        return $this->tpSituacaoCpf;
    }

    /**
     * @param string $nuCpf
     */
    public function setNuCpf($nuCpf)
    {
        $this->nuCpf = $nuCpf;
    }

    /**
     * @return string
     */
    public function getNuCpf()
    {
        return $this->nuCpf;
    }

    /**
     * @return ArrayCollection<PessoaPerfil>
     */
    public function getPessoasPerfis()
    {
        return $this->pessoasPerfis;
    }

    /**
     * @return ArrayCollection<PessoaPerfil>
     */
    public function getPessoasPerfisAtivos()
    {
        return $this->pessoasPerfis->filter(function ($pessoaPerfil) {
            return $pessoaPerfil->isAtivo();
        });
    }

    /**
     * @return ArrayCollection<PessoaPerfil>
     */
    public function hasOnePessoaPerfiLAtivo()
    {
        $perfis = $this->pessoasPerfis->filter(function ($pessoaPerfil) {
            return $pessoaPerfil->isAtivo();
        })->count();

        if ($perfis <= 1) {
            return true;
        }

        return false;
    }

    /**
     * @return Pessoa
     */
    public function getPessoa()
    {
        return $this->pessoa;
    }

    /**
     * @return DadoPessoal
     */
    public function getDadoPessoal()
    {
        return $this->dadoPessoal;
    }

    private function inativarAllPessoasPerfis()
    {
        foreach ($this->pessoasPerfis->toArray() as $pessoaPerfil) {
            $pessoaPerfil->inativar();
        }
    }

    /**
     * @param Perfil $perfil
     * @return boolean
     */
    public function isPerfilVinculado(Perfil $perfil)
    {
        foreach ($this->pessoasPerfis->toArray() as $perfilVinculado) {
            if ($perfilVinculado->getPerfil()->getNoRole() == $perfil->getNoRole()) {
                return $perfilVinculado;
            }
        }

        return false;
    }

    /**
     * @param Perfil $perfil
     * @return PessoaPerfil
     */
    public function addPerfil(Perfil $perfil)
    {
        if ($perfilVinculado = $this->isPerfilVinculado($perfil)) {
            $perfilVinculado->ativar();
        } else {
            $perfilVinculado = new PessoaPerfil($perfil, $this);
            $this->pessoasPerfis->add($perfilVinculado);
        }

        return $perfilVinculado;
    }

    /**
     * @return array
     */
    public function getPerfis()
    {
        $perfis = [];

        foreach ($this->getPessoasPerfis() as $perfil) {
            $perfis[$perfil->getPerfil()->getDsPerfil()] = $perfil->getPerfil();
        }

        return $perfis;
    }

    /**
     * @param Perfil $perfil
     * @return PessoaPerfil
     */
    public function getPessoaPerfil($perfil)
    {
        $pessoasPerfis = $this->pessoasPerfis->filter(function ($pessoaPerfil) use ($perfil) {
            if ($pessoaPerfil->getPerfil() == $perfil) {
                return $pessoaPerfil;
            }
        });

        return $pessoasPerfis->isEmpty() ? null : $pessoasPerfis->first();
    }

    /**
     * @return Usuario
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * @param Projeto $projeto
     * @return boolean
     */
    public function isProjetoVinculado(Projeto $projeto)
    {
        foreach ($this->pessoasPerfis->toArray() as $pessoaPerfil) {
            foreach ($pessoaPerfil->getProjetosPessoasAtivos() as $projetoVinculado) {
                if ($projetoVinculado->getProjeto() == $projeto) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     *
     * @return boolean
     */
    public function isCpfRegular()
    {
        return $this->getTpSituacaoCpf() === 0;
    }

    /**
     *
     * //* @param \AppBundle\Entity\AgenciaBancaria $agencia
     * @param \AppBundle\Entity\Banco $banco
     * @param string $agencia
     * @param string $conta
     */
    //public function setDadoPessoal(AgenciaBancaria $agencia, Banco $banco)
    public function setDadoPessoal(Banco $banco, $agencia, $conta)
    {
        $this->dadoPessoal = new DadoPessoal($this, $banco, $agencia, $conta);
    }
}
