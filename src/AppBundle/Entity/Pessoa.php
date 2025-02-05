<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\Municipio;

/**
 * Pessoa
 *
 * @ORM\Table(name="DBPESSOA.TB_PESSOA")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PessoaRepository")
 */
class Pessoa extends AbstractEntity
{
    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(name="NU_CPF_CNPJ_PESSOA", type="string", length=14, nullable=false)
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $nuCpfCnpjPessoa;

    /**
     * @var Municipio
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Municipio")
     * @ORM\JoinColumn(name="CO_MUNICIPIO_IBGE", referencedColumnName="CO_MUNICIPIO_IBGE")
     */
    private $coMunicipioIbge;

    /**
     * @var string
     *
     * @ORM\Column(name="DS_COMPLEMENTO", type="string", length=160, nullable=true)
     */
    private $dsComplemento;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DT_ATUALIZACAO_RFB", type="datetime", nullable=true)
     */
    private $dtAtualizacaoRfb;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DT_PROCESSAMENTO", type="datetime", nullable=true)
     */
    private $dtProcessamento;

    /**
     * @var string
     *
     * @ORM\Column(name="NO_BAIRRO", type="string", length=50, nullable=true)
     */
    private $noBairro;

    /**
     * @var string
     *
     * @ORM\Column(name="NO_LOGRADOURO", type="string", length=100, nullable=true)
     */
    private $noLogradouro;

    /**
     * @var string
     *
     * @ORM\Column(name="NO_MUNICIPIO", type="string", length=50, nullable=true)
     */
    private $noMunicipio;

    /**
     * @var string
     *
     * @ORM\Column(name="NO_PESSOA", type="string", length=150, nullable=false)
     */
    private $noPessoa;

    /**
     * @var string
     *
     * @ORM\Column(name="NU_CEP", type="string", length=8, nullable=true)
     */
    private $nuCep;

    /**
     * @var string
     *
     * @ORM\Column(name="NU_DDD", type="string", length=4, nullable=true)
     */
    private $nuDdd;

    /**
     * @var string
     *
     * @ORM\Column(name="NU_DDI", type="string", length=5, nullable=true)
     */
    private $nuDdi;

    /**
     * @var string
     *
     * @ORM\Column(name="NU_LOGRADOURO", type="string", length=7, nullable=true)
     */
    private $nuLogradouro;

    /**
     * @var string
     *
     * @ORM\Column(name="NU_RAMAL", type="string", length=5, nullable=true)
     */
    private $nuRamal;

    /**
     * @var string
     *
     * @ORM\Column(name="NU_TELEFONE", type="string", length=11, nullable=true)
     */
    private $nuTelefone;

    /**
     * @var string
     *
     * @ORM\Column(name="SG_UF", type="string", length=2, nullable=true)
     */
    private $sgUf;

    /**
     * @var string
     *
     * @ORM\Column(name="ST_REGISTRO_ATIVO", type="string", length=1, nullable=false)
     */
    private $stRegistroAtivo;
    
    /**
     *
     * @var ArrayCollection Telefone
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Telefone", mappedBy="pessoa", cascade={"persist"})
     * @ORM\OrderBy({"coSeqTelefone" = "DESC"})
     */
    private $telefones;
    
    /**
     * @var Endereco
     * 
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Endereco", mappedBy="pessoa", cascade={"persist"})
     */
    private $enderecos;
    
    /**
     * @var ArrayCollection EnderecoWeb
     * 
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\EnderecoWeb", mappedBy="pessoa", cascade={"persist"})
     * @ORM\OrderBy({"coSeqEnderecoWeb" = "DESC"})
     */
    private $enderecosWeb;

    public function __construct()
    {
        $this->telefones    = new ArrayCollection();
        $this->enderecos = new ArrayCollection();
        $this->enderecosWeb = new ArrayCollection();
    }

    /**
     * Set nuCpfCnpjPessoa
     *
     * @param string $nuCpfCnpjPessoa
     * @return Pessoa
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
     * Set coMunicipioIbge
     *
     * @param Municipio $coMunicipioIbge
     * @return Pessoa
     */
    public function setCoMunicipioIbge(Municipio $coMunicipioIbge)
    {
        $this->coMunicipioIbge = $coMunicipioIbge;

        return $this;
    }

    /**
     * Get coMunicipioIbge
     *
     * @return Municipio
     */
    public function getCoMunicipioIbge()
    {
        return $this->coMunicipioIbge;
    }

    /**
     * Set dsComplemento
     *
     * @param string $dsComplemento
     * @return Pessoa
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
     * Set dtAtualizacaoRfb
     *
     * @param \DateTime $dtAtualizacaoRfb
     * @return Pessoa
     */
    public function setDtAtualizacaoRfb($dtAtualizacaoRfb)
    {
        $this->dtAtualizacaoRfb = $dtAtualizacaoRfb;

        return $this;
    }

    /**
     * Get dtAtualizacaoRfb
     *
     * @return \DateTime 
     */
    public function getDtAtualizacaoRfb()
    {
        return $this->dtAtualizacaoRfb;
    }

    /**
     * Set dtProcessamento
     *
     * @param \DateTime $dtProcessamento
     * @return Pessoa
     */
    public function setDtProcessamento($dtProcessamento)
    {
        $this->dtProcessamento = $dtProcessamento;

        return $this;
    }

    /**
     * Get dtProcessamento
     *
     * @return \DateTime 
     */
    public function getDtProcessamento()
    {
        return $this->dtProcessamento;
    }

    /**
     * Set noBairro
     *
     * @param string $noBairro
     * @return Pessoa
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
     * Set noLogradouro
     *
     * @param string $noLogradouro
     * @return Pessoa
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
     * Set noMunicipio
     *
     * @param string $noMunicipio
     * @return Pessoa
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
     * Set noPessoa
     *
     * @param string $noPessoa
     * @return Pessoa
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
     * Set nuCep
     *
     * @param string $nuCep
     * @return Pessoa
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
     * Set nuDdd
     *
     * @param string $nuDdd
     * @return Pessoa
     */
    public function setNuDdd($nuDdd)
    {
        $this->nuDdd = $nuDdd;

        return $this;
    }

    /**
     * Get nuDdd
     *
     * @return string 
     */
    public function getNuDdd()
    {
        return $this->nuDdd;
    }

    /**
     * Set nuDdi
     *
     * @param string $nuDdi
     * @return Pessoa
     */
    public function setNuDdi($nuDdi)
    {
        $this->nuDdi = $nuDdi;

        return $this;
    }

    /**
     * Get nuDdi
     *
     * @return string 
     */
    public function getNuDdi()
    {
        return $this->nuDdi;
    }

    /**
     * Set nuLogradouro
     *
     * @param string $nuLogradouro
     * @return Pessoa
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
     * Set nuRamal
     *
     * @param string $nuRamal
     * @return Pessoa
     */
    public function setNuRamal($nuRamal)
    {
        $this->nuRamal = $nuRamal;

        return $this;
    }

    /**
     * Get nuRamal
     *
     * @return string 
     */
    public function getNuRamal()
    {
        return $this->nuRamal;
    }

    /**
     * Set nuTelefone
     *
     * @param string $nuTelefone
     * @return Pessoa
     */
    public function setNuTelefone($nuTelefone)
    {
        $this->nuTelefone = $nuTelefone;

        return $this;
    }

    /**
     * Get nuTelefone
     *
     * @return string 
     */
    public function getNuTelefone()
    {
        return $this->nuTelefone;
    }

    /**
     * Set sgUf
     *
     * @param string $sgUf
     * @return Pessoa
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
     * Set stRegistroAtivo
     *
     * @param string $stRegistroAtivo
     * @return Pessoa
     */
    public function setStRegistroAtivo($stRegistroAtivo)
    {
        $this->stRegistroAtivo = $stRegistroAtivo;

        return $this;
    }

    /**
     * Get stRegistroAtivo
     *
     * @return string 
     */
    public function getStRegistroAtivo()
    {
        return $this->stRegistroAtivo;
    }
    
    /**
     * @return ArrayCollection<Telefone>
     */
    public function getTelefones()
    {
        return $this->telefones;
    }
    
    /**
     * @return ArrayCollection<Telefone>
     */
    public function getTelefonesAtivos()
    {
        return $this->telefones->filter(function($telefone){
            return $telefone->isAtivo();
        });
    }
    
    /**
     * @return ArrayCollection<Telefone>
     */
    public function getTelefonesAtivosList()
    {
        $collection = $this->getTelefonesAtivos();
        
        return $collection->map(function($telefone){
           return $telefone->getDsTelefone($telefone->getTpTelefone()) . ' (' . $telefone->getNuDdd() . ') ' . $telefone->getNuTelefone();
        });
    }
    
    /**
     * @return ArrayCollection<Endereco>
     */
    public function getEnderecos()
    {
        return $this->enderecos;
    }
    
    public function getEnderecoAtivo()
    {
        return $this->enderecos->filter(function($endereco){
            return $endereco->isAtivo();
        })->first();
    }
    
    /**
     * @return ArrayCollection<EnderecoWeb>
     */
    public function getEnderecosWeb()
    {
        return $this->enderecosWeb;
    }

    /**
     * @return EnderecoWeb|null
     */
    public function getEnderecoWebAtivo()
    {
        return $this->enderecosWeb->filter(function($enderecoWeb){
            return $enderecoWeb->isAtivo();
        })->first();
    }

    /**
     * @return string
     */
    public function getEnderecoWebRecente()
    {
        return $this->enderecosWeb->isEmpty() ? null : $this->enderecosWeb->first();
    }
    
    /**
     * @return ArrayCollection<EnderecoWeb>
     */
    public function getEnderecosWebAtivos()
    {
        return $this->enderecosWeb->filter(function ($email) {
            return $email->isAtivo();
        });
    }
    
    /**
     * @return array
     */
    public function getEnderecosWebAtivosList()
    {
        $collection = $this->getEnderecosWebAtivos();
        
        return $collection->map(function($enderecoWeb){
           return $enderecoWeb->getDsEnderecoWeb(); 
        });
    }
    
    public function inativarAllEnderecos()
    {
        foreach($this->enderecos as $endereco) {
            $endereco->inativar();
        }
    }
    
    public function isEnderecoVinculado(Cep $cep)
    {
        foreach($this->enderecos as $enderecoVinculado) {
            if($cep->getNuCep() == $enderecoVinculado->getCep()->getNuCep()) {
                return $enderecoVinculado;
            }
        }
        
        return false;
    }
    
    /**
     * @param Cep $cep
     * @param Municipio $municipio
     * @param string $noLogradouro
     * @param string $dsComplemento
     * @param string $noBairro
     * @param string $nuLogradouro
     * @return Pessoa
     */
    public function addEndereco( 
        Cep $cep,
        Municipio $municipio,
        $noLogradouro, 
        $dsComplemento, 
        $noBairro, 
        $nuLogradouro
    ) {
        $this->inativarAllEnderecos();
        if($enderecoVinculado = $this->isEnderecoVinculado($cep)) {
            $enderecoVinculado->ativar();
            $enderecoVinculado->setDsComplemento($dsComplemento);
        } else {
            $enderecoVinculado = new Endereco(
                $this, 
                $cep, 
                $municipio, 
                $noLogradouro, 
                $dsComplemento, 
                $noBairro, 
                $nuLogradouro
            );
            $this->enderecos->add($enderecoVinculado);
        }
        return $enderecoVinculado;
    }
    
    /**
     * @param Telefone $telefone
     * @return boolean
     */
    public function isTelefoneVinculado(Telefone $telefone)
    {
        foreach ($this->telefones->toArray() as $telefoneVinculado) {
            if (
                $telefoneVinculado->getNuDdd() == $telefone->getNuDdd() &&
                ($telefoneVinculado->getNuTelefone() == $telefone->getNuTelefone() || $telefoneVinculado->getNuTelefone() == $telefone->getNuTelefoneClean()) &&
                $telefoneVinculado->getTpTelefone() == $telefone->getTpTelefone()
            ) {
                return $telefoneVinculado;
            }
        }
        
        return false;
    }
    
    /**
     * @param string $nuDdd
     * @param string $nuTelefone
     * @param string $tpTelefone
     * @return Pessoa
     */
    public function addTelefone($nuDdd, $nuTelefone, $tpTelefone)
    {
        $telefone = new Telefone($this, $nuDdd, $nuTelefone, $tpTelefone);
        
        if ($telefoneVinculado = $this->isTelefoneVinculado($telefone)) {
            $telefoneVinculado->ativar();
        } else {
            $this->telefones->add($telefone);
        }
        
        return $this;
    }
    
    /**
     * @param mixed $enderecoWeb
     * @return boolean
     */
    public function hasEnderecoWeb($enderecoWeb)
    {
        if ($enderecoWeb instanceof EnderecoWeb) {
            $enderecoWeb = $enderecoWeb->getDsEnderecoWeb();
        }
        
        foreach ($this->enderecosWeb as $enderecoWebVinculado) {            
            if ($enderecoWebVinculado->getDsEnderecoWeb() == $enderecoWeb) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * @param string $dsEnderecoWeb
     * @return boolean|EnderecoWeb
     */
    public function isEnderecoWebVinculado($dsEnderecoWeb)
    {
        foreach ($this->enderecosWeb->toArray() as $enderecoWebVinculado) {
            if ($enderecoWebVinculado->getDsEnderecoWeb() == $dsEnderecoWeb) {
                return $enderecoWebVinculado;
            }
        }
        
        return false;
    }
    
    /**
     * 
     * @param EnderecoWeb $dsEnderecoWeb
     * @return Pessoa
     */
    public function addEnderecoWeb($dsEnderecoWeb)
    {
        $this->inativarAllEnderecoWeb();
        if ($enderecoWebVinculado = $this->isEnderecoWebVinculado($dsEnderecoWeb)) {
            $enderecoWebVinculado->ativar();
        } else {
            $enderecoWeb = new EnderecoWeb($this, $dsEnderecoWeb);
            $this->enderecosWeb->add($enderecoWeb);
        }
        
        return $this;
    }
    
    public function inativarAllEnderecoWeb() {
        foreach($this->enderecosWeb->toArray() as $enderecoWeb) {
            $enderecoWeb->inativar();
        }
    }
    
    /**
     * Inativa todos os telefones ligadas ao projeto
     * @return Pessoa
     */
    public function inativarAllTelefones()
    {
        foreach ($this->telefones->toArray() as $telefone ){
            $telefone->inativar();
        }
        return $this;
    }
    
    /**
     * @return Telefone
     */
    public function getTelefoneResidencial()
    {
        return $this->getTelefone(1);
    }
    
    /**
     * @return Telefone
     */
    public function getTelefoneComercial()
    {
        return $this->getTelefone(2);
    }
    
    /**
     * @return Telefone 
     */
    public function getTelefoneCelular()
    {
        return $this->getTelefone(3);
    }
    
    /**
     * @param string $tipo
     * @return string
     */
    private function getTelefone($tipo)
    {
        $telefones = $this->telefones->filter(function ($telefone) use ($tipo) {
            return $telefone->getTpTelefone() == $tipo && $telefone->isAtivo();
        });
        
        if (!$telefones->isEmpty()) {
            return '(' . $telefones->first()->getNuDdd() . ') ' . $telefones->first()->getNuTelefone(); 
        }
        
        return null;
    }
}
