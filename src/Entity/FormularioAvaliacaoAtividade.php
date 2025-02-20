<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="DBPETINFOSD.TB_FORMULARIO_AVALIACAO_ATIVID")
 * @ORM\Entity(repositoryClass="\App\Repository\FormularioAvaliacaoAtividadeRepository")
 */
class FormularioAvaliacaoAtividade extends AbstractEntity
{
    use \App\Traits\DataInclusaoTrait;
    use \App\Traits\DeleteLogicoTrait;
    
    /**
     *
     * @var integer
     * 
     * @ORM\Id
     * @ORM\Column(name="CO_SEQ_FORM_AVALIACAO_ATIVD", type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="DBPETINFOSD.SQ_FORMAVAL_COSEQFORMAVLATIV", allocationSize=1, initialValue=1)
     */
    private $coSeqFormAvaliacaoAtivd;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NO_FORMULARIO", type="string", length=100, nullable=false)
     */
    private $noFormulario;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="DS_AVALIACAO", type="string", length=2000, nullable=true)
     */
    private $dsAvaliacao;
    
    /**
     *
     * @var Periodicidade
     * 
     * @ORM\ManyToOne(targetEntity="\App\Entity\Periodicidade")
     * @ORM\JoinColumn(name="CO_PERIODICIDADE", referencedColumnName="CO_SEQ_PERIODICIDADE", nullable=false)
     */
    private $periodicidade;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="DS_URL_FORMULARIO", type="string", length=200, nullable=false)
     */
    private $dsUrlFormulario;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NO_ARQUIVO_FORMULARIO", type="string", length=200, nullable=true)
     */
    private $noArquivoFormulario;
    
    /**
     *
     * @var ArrayCollection<\App\Entity\PerfilFormularioAvaliacaoAtividade>
     * 
     * @ORM\OneToMany(targetEntity="\App\Entity\PerfilFormularioAvaliacaoAtividade", mappedBy="formularioAvaliacaoAtividade", cascade={"persist", "persist"}, orphanRemoval=true)
     */
    private $perfilFormularioAvaliacaoAtividade;
    
    /**
     *
     * @var ArrayCollection<\App\Entity\EnvioFormularioAvaliacaoAtividade>
     * 
     * @ORM\OneToMany(targetEntity="\App\Entity\EnvioFormularioAvaliacaoAtividade", mappedBy="formularioAvaliacaoAtividade", orphanRemoval=true)
     */
    private $envioFormularioAvaliacaoAtividade;
    
    /**
     * 
     * @param string $noFormulario
     * @param \App\Entity\Periodicidade $periodicidade
     * @param string $dsUrlFormulario
     * @param string $noArquivoFormulario
     * @param string $dsAvaliacao
     */
    public function __construct($noFormulario, Periodicidade $periodicidade, $dsUrlFormulario, $noArquivoFormulario, $dsAvaliacao = null)
    {
        $this->noFormulario = $noFormulario;
        $this->dsAvaliacao = $dsAvaliacao;
        $this->periodicidade = $periodicidade;
        $this->dsUrlFormulario = $dsUrlFormulario;
        $this->noArquivoFormulario = $noArquivoFormulario;
        $this->perfilFormularioAvaliacaoAtividade = new ArrayCollection();
        $this->envioFormularioAvaliacaoAtividade = new ArrayCollection();
        $this->dtInclusao = new \DateTime();
        $this->stRegistroAtivo = 'S';
    }

    /**
     * 
     * @return integer
     */
    public function getCoSeqFormAvaliacaoAtivd()
    {
        return $this->coSeqFormAvaliacaoAtivd;
    }

    /**
     * 
     * @return string
     */
    public function getNoFormulario()
    {
        return $this->noFormulario;
    }

    /**
     * 
     * @return string
     */
    public function getDsAvaliacao()
    {
        return $this->dsAvaliacao;
    }

    /**
     * 
     * @return Periodicidade
     */
    public function getPeriodicidade()
    {
        return $this->periodicidade;
    }

    /**
     * 
     * @return string
     */
    public function getDsUrlFormulario()
    {
        return $this->dsUrlFormulario;
    }

    /**
     * 
     * @return string
     */
    public function getNoArquivoFormulario()
    {
        return $this->noArquivoFormulario;
    }

    /**
     * 
     * @return ArrayCollection<\App\Entity\PerfilFormularioAvaliacaoAtividade>
     */
    public function getPerfilFormularioAvaliacaoAtividade()
    {
        return $this->perfilFormularioAvaliacaoAtividade;
    }
    
    /**
     * 
     * @return ArrayCollection<\App\Entity\PerfilFormularioAvaliacaoAtividade>
     */
    public function getPerfilFormularioAvaliacaoAtividadeAtivos()
    {
        return $this->perfilFormularioAvaliacaoAtividade->filter(function ($perfilFormularioAvaliacaoAtividade) {
            return $perfilFormularioAvaliacaoAtividade->isAtivo();
        });
    }
    
    /**
     * 
     * @return string
     */
    public function getPerfisPorExtenso()
    {
        $perfis = $this->getPerfilFormularioAvaliacaoAtividadeAtivos()->map(function ($perfilFormularioAvaliacaoAtividade) {
            return $perfilFormularioAvaliacaoAtividade->getPerfil()->getDsPerfil();
        })->toArray();
        
        sort($perfis);
        
        return implode(', ', $perfis);
    }
    
    /**
     * 
     * @return ArrayCollection<\App\Entity\EnvioFormularioAvaliacaoAtividade>
     */
    public function getEnvioFormularioAvaliacaoAtividadeAtivos()
    {
        return $this->envioFormularioAvaliacaoAtividade->filter(function ($envioFormularioAvaliacaoAtividade) {
            return $envioFormularioAvaliacaoAtividade->isAtivo();
        });
    }
        
    /**
     * 
     * @param string $noFormulario
     */
    public function setNoFormulario($noFormulario)
    {
        $this->noFormulario = $noFormulario;
    }

    /**
     * 
     * @param string $dsAvaliacao
     */
    public function setDsAvaliacao($dsAvaliacao)
    {
        $this->dsAvaliacao = $dsAvaliacao;
    }

    /**
     * 
     * @param \App\Entity\Periodicidade $periodicidade
     */
    public function setPeriodicidade(Periodicidade $periodicidade)
    {
        $this->periodicidade = $periodicidade;
    }

    /**
     * 
     * @param string $dsUrlFormulario
     */
    public function setDsUrlFormulario($dsUrlFormulario)
    {
        $this->dsUrlFormulario = $dsUrlFormulario;
    }

    /**
     * 
     * @param string $noArquivoFormulario
     */
    public function setNoArquivoFormulario($noArquivoFormulario)
    {
        $this->noArquivoFormulario = $noArquivoFormulario;
    }

        
    /**
     * 
     * @param \App\Entity\Perfil $perfil
     */
    public function addPerfilFormularioAvaliacaoAtividade(Perfil $perfil)
    {
        if ($this->hasPerfil($perfil)) {
            $perfilFormularioAvaliacaoAtividade = $this->getPerfilFormularioAvaliacaoAtividadeByPerfil($perfil);
            $perfilFormularioAvaliacaoAtividade->ativar();
        } else {
            $this->perfilFormularioAvaliacaoAtividade->add(new PerfilFormularioAvaliacaoAtividade($perfil, $this));
        }
    }
    
    public function inativarPerfisFormularioAvaliacaoAtividade()
    {
        foreach ($this->getPerfilFormularioAvaliacaoAtividadeAtivos() as $perfilFormularioAvaliacaoAtividade) {
            $perfilFormularioAvaliacaoAtividade->inativar();
        }
    }
    
    /**
     * 
     * @param \App\Entity\Perfil $perfil
     * @return boolean
     */
    public function hasPerfil(Perfil $perfil)
    {
        return $this->getPerfilFormularioAvaliacaoAtividade()->exists(function ($key, $perfilFormularioAvaliacaoAtividade) use ($perfil) {
            return $perfilFormularioAvaliacaoAtividade->getPerfil() == $perfil;
        });
    }
    
    /**
     * 
     * @param \App\Entity\Perfil $perfil
     * @return type
     */
    public function getPerfilFormularioAvaliacaoAtividadeByPerfil(Perfil $perfil)
    {
        return $this->getPerfilFormularioAvaliacaoAtividade()->filter(function ($perfilFormularioAvaliacaoAtividade) use ($perfil) {
            return $perfilFormularioAvaliacaoAtividade->getPerfil() == $perfil;
        })->first();
    }
}
