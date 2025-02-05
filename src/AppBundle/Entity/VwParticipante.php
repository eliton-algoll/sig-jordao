<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VwParticipante
 *
 * @ORM\Table(name="DBPET.VW_PARTICIPANTE")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\VwParticipanteRepository")
 */
class VwParticipante extends AbstractEntity
{
    
    /**
     * @var int
     *
     * @ORM\Column(name="CO_SEQ_PROJETO", type="integer")
     */
    private $coSeqProjeto;

    /**
     * @var string
     *
     * @ORM\Column(name="NU_SIPAR", type="string", length=20)
     */
    private $nuSipar;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="CO_SEQ_PROJETO_PESSOA", type="integer")
     */
    private $coSeqProjetoPessoa;

    /**
     * @var string
     *
     * @ORM\Column(name="ST_REGISTROATIVO_PROJPESSOA", type="string", length=1)
     */
    private $stRegistroativoProjpessoa;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DT_INCLUSAO_PROJPESSOA", type="datetime")
     */
    private $dtInclusaoProjpessoa;

    /**
     * @var string
     *
     * @ORM\Column(name="ST_VOLUNTARIO_PROJETO", type="string", length=1)
     */
    private $stVoluntarioProjeto;

    /**
     * @var int
     *
     * @ORM\Column(name="CO_SEQ_PESSOA_PERFIL", type="integer")
     */
    private $coSeqPessoaPerfil;

    /**
     * @var string
     *
     * @ORM\Column(name="ST_REGISTROATIVO_PESPERFIL", type="string", length=1)
     */
    private $stRegistroativoPesperfil;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DT_INCLUSAO_PES_PERFIL", type="datetime")
     */
    private $dtInclusaoPesPerfil;

    /**
     * @var int
     *
     * @ORM\Column(name="CO_SEQ_PERFIL", type="integer")
     */
    private $coSeqPerfil;

    /**
     * @var string
     *
     * @ORM\Column(name="DS_PERFIL", type="string", length=30)
     */
    private $dsPerfil;

    /**
     * @var int
     *
     * @ORM\Column(name="VL_BOLSA", type="integer")
     */
    private $vlBolsa;

    /**
     * @var string
     *
     * @ORM\Column(name="NU_CPF_CNPJ_PESSOA", type="string", length=14)
     */
    private $nuCpfCnpjPessoa;

    /**
     * @var string
     *
     * @ORM\Column(name="NO_PESSOA", type="string", length=150)
     */
    private $noPessoa;

    /**
     * @var string
     *
     * @ORM\Column(name="NO_MAE", type="string", length=70)
     */
    private $noMae;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DT_NASCIMENTO", type="datetime")
     */
    private $dtNascimento;

    /**
     * @var string
     *
     * @ORM\Column(name="CO_MUNICIPIO_IBGE", type="string", length=6)
     */
    private $coMunicipioIbge;

    /**
     * @var string
     *
     * @ORM\Column(name="SG_UF", type="string", length=2)
     */
    private $sgUf;

    /**
     * @var string
     *
     * @ORM\Column(name="NO_MUNICIPIO", type="string", length=60)
     */
    private $noMunicipio;

    /**
     * @var string
     *
     * @ORM\Column(name="NU_CEP", type="string", length=8)
     */
    private $nuCep;

    /**
     * @var string
     *
     * @ORM\Column(name="NO_LOGRADOURO", type="string", length=50)
     */
    private $noLogradouro;

    /**
     * @var string
     *
     * @ORM\Column(name="DS_COMPLEMENTO", type="string", length=15)
     */
    private $dsComplemento;

    /**
     * @var string
     *
     * @ORM\Column(name="NO_BAIRRO", type="string", length=30)
     */
    private $noBairro;

    /**
     * @var string
     *
     * @ORM\Column(name="NU_LOGRADOURO", type="string", length=7)
     */
    private $nuLogradouro;

    /**
     * @var string
     *
     * @ORM\Column(name="DS_EMAIL", type="text")
     */
    private $dsEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="CO_BANCO", type="string", length=4)
     */
    private $coBanco;

    /**
     * @var string
     *
     * @ORM\Column(name="NO_BANCO", type="string", length=50)
     */
    private $noBanco;

    /**
     * @var string
     *
     * @ORM\Column(name="CO_AGENCIA", type="string", length=6)
     */
    private $coAgencia;    

    /**
     * @var string
     *
     * @ORM\Column(name="DS_TITULACAO", type="string", length=50)
     */
    private $dsTitulacao;

    /**
     * @var string
     *
     * @ORM\Column(name="NU_ANO_INGRESSO", type="string", length=4)
     */
    private $nuAnoIngresso;

    /**
     * @var string
     *
     * @ORM\Column(name="NU_MATRICULA", type="string", length=30)
     */
    private $nuMatricula;

    /**
     * @var int
     *
     * @ORM\Column(name="NU_SEMESTRE", type="integer")
     */
    private $nuSemestre;

    /**
     * @var string
     *
     * @ORM\Column(name="DS_CATEGORIA_PROFISSIONAL", type="string", length=100)
     */
    private $dsCategoriaProfissional;

    /**
     * @var string
     *
     * @ORM\Column(name="CURSO_GRADUACAO", type="text")
     */
    private $cursoGraduacao;

    /**
     * @var string
     *
     * @ORM\Column(name="NO_INSTITUICAO_PROJETO", type="text")
     */
    private $noInstituicaoProjeto;

    /**
     * @var string
     *
     * @ORM\Column(name="SECRETARIA_SAUDE", type="text")
     */
    private $secretariaSaude;

    /**
     *
     * @var \DateTime
     * 
     * @ORM\Column(name="DT_DESLIGAMENTO", type="datetime") 
     */
    private $dtDesligamento;

    /**
     * Set coSeqProjeto
     *
     * @param integer $coSeqProjeto
     *
     * @return VwParticipante
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
     * Set nuSipar
     *
     * @param string $nuSipar
     *
     * @return VwParticipante
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
     * Set coSeqProjetoPessoa
     *
     * @param integer $coSeqProjetoPessoa
     *
     * @return VwParticipante
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
     * Set stRegistroativoProjpessoa
     *
     * @param string $stRegistroativoProjpessoa
     *
     * @return VwParticipante
     */
    public function setStRegistroativoProjpessoa($stRegistroativoProjpessoa)
    {
        $this->stRegistroativoProjpessoa = $stRegistroativoProjpessoa;

        return $this;
    }

    /**
     * Get stRegistroativoProjpessoa
     *
     * @return string
     */
    public function getStRegistroativoProjpessoa()
    {
        return $this->stRegistroativoProjpessoa;
    }

    /**
     * Set dtInclusaoProjpessoa
     *
     * @param \DateTime $dtInclusaoProjpessoa
     *
     * @return VwParticipante
     */
    public function setDtInclusaoProjpessoa($dtInclusaoProjpessoa)
    {
        $this->dtInclusaoProjpessoa = $dtInclusaoProjpessoa;

        return $this;
    }

    /**
     * Get dtInclusaoProjpessoa
     *
     * @return \DateTime
     */
    public function getDtInclusaoProjpessoa()
    {
        return $this->dtInclusaoProjpessoa;
    }

    /**
     * Set stVoluntarioProjeto
     *
     * @param string $stVoluntarioProjeto
     *
     * @return VwParticipante
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
     * Set coSeqPessoaPerfil
     *
     * @param integer $coSeqPessoaPerfil
     *
     * @return VwParticipante
     */
    public function setCoSeqPessoaPerfil($coSeqPessoaPerfil)
    {
        $this->coSeqPessoaPerfil = $coSeqPessoaPerfil;

        return $this;
    }

    /**
     * Get coSeqPessoaPerfil
     *
     * @return int
     */
    public function getCoSeqPessoaPerfil()
    {
        return $this->coSeqPessoaPerfil;
    }

    /**
     * Set stRegistroativoPesperfil
     *
     * @param string $stRegistroativoPesperfil
     *
     * @return VwParticipante
     */
    public function setStRegistroativoPesperfil($stRegistroativoPesperfil)
    {
        $this->stRegistroativoPesperfil = $stRegistroativoPesperfil;

        return $this;
    }

    /**
     * Get stRegistroativoPesperfil
     *
     * @return string
     */
    public function getStRegistroativoPesperfil()
    {
        return $this->stRegistroativoPesperfil;
    }

    /**
     * Set dtInclusaoPesPerfil
     *
     * @param \DateTime $dtInclusaoPesPerfil
     *
     * @return VwParticipante
     */
    public function setDtInclusaoPesPerfil($dtInclusaoPesPerfil)
    {
        $this->dtInclusaoPesPerfil = $dtInclusaoPesPerfil;

        return $this;
    }

    /**
     * Get dtInclusaoPesPerfil
     *
     * @return \DateTime
     */
    public function getDtInclusaoPesPerfil()
    {
        return $this->dtInclusaoPesPerfil;
    }

    /**
     * Set coSeqPerfil
     *
     * @param integer $coSeqPerfil
     *
     * @return VwParticipante
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
     * @return VwParticipante
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
     * Set vlBolsa
     *
     * @param integer $vlBolsa
     *
     * @return VwParticipante
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
     * Set nuCpfCnpjPessoa
     *
     * @param string $nuCpfCnpjPessoa
     *
     * @return VwParticipante
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
     * Set noPessoa
     *
     * @param string $noPessoa
     *
     * @return VwParticipante
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
     * Set noMae
     *
     * @param string $noMae
     *
     * @return VwParticipante
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
     * Set dtNascimento
     *
     * @param \DateTime $dtNascimento
     *
     * @return VwParticipante
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
     * Set coMunicipioIbge
     *
     * @param string $coMunicipioIbge
     *
     * @return VwParticipante
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
     * Set sgUf
     *
     * @param string $sgUf
     *
     * @return VwParticipante
     */
    public function setSgUf($sgUf)
    {
        $this->sgUf = $sgUf;

        return $this;
    }

    /**
     * Get sgUf
     *
     * @return string
     */
    public function getSgUf()
    {
        return $this->sgUf;
    }

    /**
     * Set noMunicipio
     *
     * @param string $noMunicipio
     *
     * @return VwParticipante
     */
    public function setNoMunicipio($noMunicipio)
    {
        $this->noMunicipio = $noMunicipio;

        return $this;
    }

    /**
     * Get noMunicipio
     *
     * @return string
     */
    public function getNoMunicipio()
    {
        return $this->noMunicipio;
    }

    /**
     * Set nuCep
     *
     * @param string $nuCep
     *
     * @return VwParticipante
     */
    public function setNuCep($nuCep)
    {
        $this->nuCep = $nuCep;

        return $this;
    }

    /**
     * Get nuCep
     *
     * @return string
     */
    public function getNuCep()
    {
        return $this->nuCep;
    }

    /**
     * Set noLogradouro
     *
     * @param string $noLogradouro
     *
     * @return VwParticipante
     */
    public function setNoLogradouro($noLogradouro)
    {
        $this->noLogradouro = $noLogradouro;

        return $this;
    }

    /**
     * Get noLogradouro
     *
     * @return string
     */
    public function getNoLogradouro()
    {
        return $this->noLogradouro;
    }

    /**
     * Set dsComplemento
     *
     * @param string $dsComplemento
     *
     * @return VwParticipante
     */
    public function setDsComplemento($dsComplemento)
    {
        $this->dsComplemento = $dsComplemento;

        return $this;
    }

    /**
     * Get dsComplemento
     *
     * @return string
     */
    public function getDsComplemento()
    {
        return $this->dsComplemento;
    }

    /**
     * Set noBairro
     *
     * @param string $noBairro
     *
     * @return VwParticipante
     */
    public function setNoBairro($noBairro)
    {
        $this->noBairro = $noBairro;

        return $this;
    }

    /**
     * Get noBairro
     *
     * @return string
     */
    public function getNoBairro()
    {
        return $this->noBairro;
    }

    /**
     * Set nuLogradouro
     *
     * @param string $nuLogradouro
     *
     * @return VwParticipante
     */
    public function setNuLogradouro($nuLogradouro)
    {
        $this->nuLogradouro = $nuLogradouro;

        return $this;
    }

    /**
     * Get nuLogradouro
     *
     * @return string
     */
    public function getNuLogradouro()
    {
        return $this->nuLogradouro;
    }

    /**
     * Set dsEmail
     *
     * @param string $dsEmail
     *
     * @return VwParticipante
     */
    public function setDsEmail($dsEmail)
    {
        $this->dsEmail = $dsEmail;

        return $this;
    }

    /**
     * Get dsEmail
     *
     * @return string
     */
    public function getDsEmail()
    {
        return $this->dsEmail;
    }

    /**
     * Set coBanco
     *
     * @param string $coBanco
     *
     * @return VwParticipante
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
     * Set noBanco
     *
     * @param string $noBanco
     *
     * @return VwParticipante
     */
    public function setNoBanco($noBanco)
    {
        $this->noBanco = $noBanco;

        return $this;
    }

    /**
     * Get noBanco
     *
     * @return string
     */
    public function getNoBanco()
    {
        return $this->noBanco;
    }

    /**
     * Set coAgencia
     *
     * @param string $coAgencia
     *
     * @return VwParticipante
     */
    public function setCoAgencia($coAgencia)
    {
        $this->coAgencia = $coAgencia;

        return $this;
    }

    /**
     * Get coAgencia
     *
     * @return string
     */
    public function getCoAgencia()
    {
        return $this->coAgencia;
    }    

    /**
     * Set dsTitulacao
     *
     * @param string $dsTitulacao
     *
     * @return VwParticipante
     */
    public function setDsTitulacao($dsTitulacao)
    {
        $this->dsTitulacao = $dsTitulacao;

        return $this;
    }

    /**
     * Get dsTitulacao
     *
     * @return string
     */
    public function getDsTitulacao()
    {
        return $this->dsTitulacao;
    }

    /**
     * Set nuAnoIngresso
     *
     * @param string $nuAnoIngresso
     *
     * @return VwParticipante
     */
    public function setNuAnoIngresso($nuAnoIngresso)
    {
        $this->nuAnoIngresso = $nuAnoIngresso;

        return $this;
    }

    /**
     * Get nuAnoIngresso
     *
     * @return string
     */
    public function getNuAnoIngresso()
    {
        return $this->nuAnoIngresso;
    }

    /**
     * Set nuMatricula
     *
     * @param string $nuMatricula
     *
     * @return VwParticipante
     */
    public function setNuMatricula($nuMatricula)
    {
        $this->nuMatricula = $nuMatricula;

        return $this;
    }

    /**
     * Get nuMatricula
     *
     * @return string
     */
    public function getNuMatricula()
    {
        return $this->nuMatricula;
    }

    /**
     * Set nuSemestre
     *
     * @param integer $nuSemestre
     *
     * @return VwParticipante
     */
    public function setNuSemestre($nuSemestre)
    {
        $this->nuSemestre = $nuSemestre;

        return $this;
    }

    /**
     * Get nuSemestre
     *
     * @return int
     */
    public function getNuSemestre()
    {
        return $this->nuSemestre;
    }

    /**
     * Set dsCategoriaProfissional
     *
     * @param string $dsCategoriaProfissional
     *
     * @return VwParticipante
     */
    public function setDsCategoriaProfissional($dsCategoriaProfissional)
    {
        $this->dsCategoriaProfissional = $dsCategoriaProfissional;

        return $this;
    }

    /**
     * Get dsCategoriaProfissional
     *
     * @return string
     */
    public function getDsCategoriaProfissional()
    {
        return $this->dsCategoriaProfissional;
    }

    /**
     * Set cursoGraduacao
     *
     * @param string $cursoGraduacao
     *
     * @return VwParticipante
     */
    public function setCursoGraduacao($cursoGraduacao)
    {
        $this->cursoGraduacao = $cursoGraduacao;

        return $this;
    }

    /**
     * Get cursoGraduacao
     *
     * @return string
     */
    public function getCursoGraduacao()
    {
        return $this->cursoGraduacao;
    }

    /**
     * Set noInstituicaoProjeto
     *
     * @param string $noInstituicaoProjeto
     *
     * @return VwParticipante
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

    /**
     * Set secretariaSaude
     *
     * @param string $secretariaSaude
     *
     * @return VwParticipante
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
    
    public function getDtDesligamento()
    {
        return $this->dtDesligamento;
    }
    
    public function isAtivoInProjeto()
    {
        return $this->stRegistroativoProjpessoa == 'S';
    }
}

