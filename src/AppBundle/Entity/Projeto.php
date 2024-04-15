<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\Perfil;

/**
 * Projeto
 *
 * @ORM\Table(name="DBPET.TB_PROJETO")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProjetoRepository")
 */
class Projeto extends AbstractEntity
{
    use \AppBundle\Traits\DeleteLogicoTrait;
    use \AppBundle\Traits\DataInclusaoTrait;
    
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\Column(name="CO_SEQ_PROJETO", type="integer")
     * @ORM\SequenceGenerator(sequenceName="DBPET.SQ_PROJETO_COSEQPROJETO", allocationSize=1, initialValue=1)
     */
    private $coSeqProjeto;

    /**
     * @var Publicacao
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Publicacao", inversedBy="projetos")
     * @ORM\JoinColumn(name="CO_PUBLICACAO", referencedColumnName="CO_SEQ_PUBLICACAO")
     */
    private $publicacao;    
    
    /**
     * @var string
     *
     * @ORM\Column(name="NU_SIPAR", type="string", length=20)
     */
    private $nuSipar;

    /**
     * @var string
     *
     * @ORM\Column(name="DS_OBSERVACAO", type="string", length=4000, nullable=true)
     */
    private $dsObservacao;

    /**
     * @var string
     *
     * @ORM\Column(name="ST_ORIENTADOR_SERVICO", type="string", length=1, nullable=false)
     */
    private $stOrientadorServico;
    
    /**
     * @var integer
     * 
     * @ORM\Column(name="QT_BOLSA", type="integer")
     */
    private $qtBolsa;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NO_DOCUMENTO_PROJETO", type="string", length=200)
     */
    private $noDocumentoProjeto;
    
    /**
     * @var ArrayCollection<ProjetoPessoa>
     * 
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ProjetoPessoa", mappedBy="projeto")
     */
    private $projetosPessoas;
    
    /**
     * @var ArrayCollection<ProjetoCampusInstituicao>
     * 
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ProjetoCampusInstituicao", mappedBy="projeto", cascade={"persist"})
     */
    private $campus;
    
    /**
     * @var ArrayCollection<AreaTematica>
     * 
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\AreaTematica", mappedBy="projeto", cascade={"persist"})
     */
    private $areasTematicas;
    
    /**
     * @var ArrayCollection<SecretariaSaude>
     * 
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\SecretariaSaude", mappedBy="projeto", cascade={"persist"})
     */
    private $secretarias;
    
    /**
     * @var ArrayCollection<ProjetoEstabelecimento>
     * 
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ProjetoEstabelecimento", mappedBy="projeto", cascade={"persist"})
     */
    private $estabelecimentos;

    /**
     * @var ArrayCollection<GrupoAtuacao>
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\GrupoAtuacao", mappedBy="projeto", cascade={"persist"})
     */
    private $gruposAtuacao;
    
    /**
     * @param Publicacao $publicacao
     * @param string $nuSipar
     * @param string|null $dsObservacao
     */
    public function __construct(
        Publicacao $publicacao,
        $nuSipar,
        $dsObservacao = null,
        $stOrientadorServico = 'N',
        $qtBolsa = null,
        $noDocumentoProjeto = null
    ) {
        $this->publicacao = $publicacao;
        $this->nuSipar = $nuSipar;
        $this->dsObservacao = $dsObservacao;
        $this->qtBolsa = $qtBolsa;
        $this->noDocumentoProjeto = $noDocumentoProjeto;
        $this->stOrientadorServico = $stOrientadorServico;
        $this->stRegistroAtivo = 'S';
        $this->dtInclusao = new \DateTime();
        $this->projetosPessoas = new ArrayCollection();
        $this->campus = new ArrayCollection();
        $this->areasTematicas = new ArrayCollection();
        $this->secretarias = new ArrayCollection();
        $this->estabelecimentos = new ArrayCollection();
        $this->gruposAtuacao = new ArrayCollection();
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
     * Get publicacao
     *
     * @return Publicacao
     */
    public function getPublicacao()
    {
        return $this->publicacao;
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
     * Get dsObservacao
     *
     * @return string
     */
    public function getDsObservacao()
    {
        return $this->dsObservacao;
    }

    /**
     * @return mixed|string|null
     */
    public function getStOrientadorServico()
    {
        return $this->stOrientadorServico;
    }
    
    /**
     * @return integer
     */
    public function getQtBolsa()
    {
        return $this->qtBolsa;
    }
    
    /**
     * 
     * @return string
     */
    public function getNoDocumentoProjeto()
    {
        return $this->noDocumentoProjeto;
    }
        
    /**
     * @return ArrayCollection<ProjetoPessoa>
     */
    public function getProjetosPessoas()
    {
        return $this->projetosPessoas;
    }
    
    /**
     * @return ArrayCollection<AreaTematica>
     */
    public function getAreasTematicas()
    {
        return $this->areasTematicas;
    }
    
    /**
     * @return ArrayCollection<AreaTematica>
     */
    public function getAreasTematicasAtivas()
    {
        return $this->areasTematicas->filter(function ($areaTematica) {
            return $areaTematica->isAtivo();
        });
    }    
    
    /**
     * @return ArrayCollection<ProjetoCampusInstituicao>
     */
    public function getCampus()
    {
        return $this->campus;
    }
    
    /**
     * @return ArrayCollection<ProjetoCampusInstituicao>
     */
    public function getCampusAtivos()
    {
        return $this->campus->filter(function ($campus) {
            return $campus->isAtivo();
        });
    }    
    
    /**
     * @return ArrayCollection<SecretariaSaude>
     */
    public function getSecretarias()
    {
        return $this->secretarias;
    }
    
    /**
     * @return ArrayCollection<SecretariaSaude>
     */
    public function getSecretariasAtivas()
    {
        return $this->secretarias->filter(function ($secretaria) {
            return $secretaria->isAtivo();
        });
    }

    /**
     * @return ArrayCollection<GrupoAtuacao>
     */
    public function getGruposAtuacao()
    {
        return $this->gruposAtuacao;
    }

    /**
     * @return ArrayCollection<GrupoAtuacao>
     */
    public function getGruposAtuacaoAtivos()
    {
        return $this->gruposAtuacao->filter(function (GrupoAtuacao $grupoAtuacao) {
            return $grupoAtuacao->isAtivo();
        });
    }

    /**
     * @return ArrayCollection<GrupoAtuacao>
     */
    public function getGruposAtuacaoAtivosEConfirmados()
    {
        return $this->gruposAtuacao->filter(function (GrupoAtuacao $grupoAtuacao) {
            return ($grupoAtuacao->isAtivo()) && ($grupoAtuacao->isConfirmado());
        });
    }

    /**
     * @return ArrayCollection<GrupoAtuacao>
     */
    public function getGruposAtuacaoAtivosENaoConfirmados()
    {
        return $this->gruposAtuacao->filter(function (GrupoAtuacao $grupoAtuacao) {
            return ($grupoAtuacao->isAtivo()) && (!$grupoAtuacao->isConfirmado());
        });
    }

    /**
     * @return ArrayCollection<GrupoAtuacao>
     */
    public function getGruposAtuacaoEixoGestaoEmSaudeAtivosENaoConfirmados()
    {
        return $this->gruposAtuacao->filter(function (GrupoAtuacao $grupoAtuacao) {
            return ($grupoAtuacao->isAtivo()) && (!$grupoAtuacao->isConfirmado()) &&
                ($grupoAtuacao->getCoEixoAtuacao() == 'G');
        });
    }

    /**
     * @return ArrayCollection<GrupoAtuacao>
     */
    public function getGruposAtuacaoEixoAssistenciaASaudeAtivosENaoConfirmados()
    {
        return $this->gruposAtuacao->filter(function (GrupoAtuacao $grupoAtuacao) {
            return ($grupoAtuacao->isAtivo()) && (!$grupoAtuacao->isConfirmado()) &&
                ($grupoAtuacao->getCoEixoAtuacao() == 'A');
        });
    }
    
    /**
     * Inativa todos os campus ligadas ao projeto
     * @return Projeto
     */
    public function removerTodosCampus()
    {
        foreach ($this->campus as $campus) {
            $campus->inativar();
        }
        return $this;
    }
    
    /**
     * Inativa todas as secretarias ligadas ao projeto
     * @return Projeto
     */
    public function removerTodasSecretarias()
    {
        foreach ($this->secretarias as $secretaria) {
            $secretaria->inativar();
        }
        return $this;
    }
    
    /**
     * Inativa todas as areas tematicas ligadas ao prpojeto
     * @return Projeto
     */
    public function removerTodasAreasTematicas()
    {
        foreach ($this->areasTematicas as $areaTematica) {
            $areaTematica->inativar();
        }
        return $this;
    }
    
    /**
     * @param Publicacao $publicacao
     * @return Projeto
     */
    public function setPublicacao(Publicacao $publicacao)
    {
        $this->publicacao = $publicacao;
        return $this;
    }
    
    /**
     * @param string $nuSipar
     * @return Projeto
     */
    public function setNuSipar($nuSipar)
    {
        $this->nuSipar = $nuSipar;
        return $this;
    }

    /**
     * @param string $dsObservacao
     * @return Projeto
     */
    public function setDsObservacao($dsObservacao)
    {
        $this->dsObservacao = $dsObservacao;
        return $this;
    }

    /**
     * @param mixed|string|null $stOrientadorServico
     */
    public function setStOrientadorServico($stOrientadorServico)
    {
        $this->stOrientadorServico = $stOrientadorServico;
        return $this;
    }
    
    /**
     * @param integer $qtBolsa
     * @return Projeto
     */
    public function setQtBolsa($qtBolsa)
    {
        $this->qtBolsa = $qtBolsa;
        return $this;
    }
    
    /**
     * 
     * @param string $noDocumentoProjeto
     */
    public function setNoDocumentoProjeto($noDocumentoProjeto)
    {
        $this->noDocumentoProjeto = $noDocumentoProjeto;
    }
            
    /**
     * @param CampusInstituicao $campus
     * @return boolean
     */
    public function isCampusVinculado(CampusInstituicao $campus)
    {
        foreach ($this->campus as $projetoCampus) {
            if ($projetoCampus->getCampus()->getCoSeqCampusInstituicao() == $campus->getCoSeqCampusInstituicao()) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * @param CampusInstituicao $campus
     * @return Projeto
     */
    public function addCampus(CampusInstituicao $campus)
    {
        if ($this->isCampusVinculado($campus)) {
            foreach ($this->campus as $projetoCampus) {
                if ($projetoCampus->getCampus()->getCoSeqCampusInstituicao() === $campus->getCoSeqCampusInstituicao()) {
                    $projetoCampus->ativar();
                    break;
                }
            }
            
        } else {
            $this->campus[] = new ProjetoCampusInstituicao($this, $campus);
        }
        
        return $this;
    }
    
    /**
     * @param TipoAreaTematica $tipoAreaTematica
     * @return boolean
     */
    public function hasAreaTematica(TipoAreaTematica $tipoAreaTematica)
    {
        foreach ($this->areasTematicas as $areaTematica) {
            if ($areaTematica->getTipoAreaTematica()->isEquals($tipoAreaTematica)) {
                return true;
            }
        }
        
        return false;
    }    
    
    /**
     * @param TipoAreaTematica $tipoAreaTematica
     * @return Projeto
     */
    public function addAreaTematica(TipoAreaTematica $tipoAreaTematica)
    {
        if ($this->hasAreaTematica($tipoAreaTematica)) {
            foreach ($this->areasTematicas as $areaTematica) {
                if ($areaTematica->getTipoAreaTematica()->isEquals($tipoAreaTematica)) {
                    $areaTematica->ativar();
                    break;
                }
            }
            
        } else {
            $this->areasTematicas[] = new AreaTematica($tipoAreaTematica, $this);
        }
        
        return $this;
    }
    
    /**
     * @param PessoaJuridica $pessoaJuridica
     * @return boolean
     */
    public function hasSecretaria(PessoaJuridica $pessoaJuridica)
    {
        foreach ($this->secretarias as $secretaria) {
            if ($secretaria->getPessoaJuridica()->getNuCnpj() === $pessoaJuridica->getNuCnpj()) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * @param PessoaJuridica $pessoaJuridica
     * @return Projeto
     */
    public function addSecretaria(PessoaJuridica $pessoaJuridica)
    {
        if ($this->hasSecretaria($pessoaJuridica)) {
            foreach ($this->secretarias as $secretaria) {
                if ($secretaria->getPessoaJuridica()->getNuCnpj() === $pessoaJuridica->getNuCnpj()) {
                    $secretaria->ativar();
                    break;
                }
            }
            
        } else {
            $this->secretarias[] = new SecretariaSaude($this, $pessoaJuridica);
        }
        
        return $this;        
    }
    
    /**
     * @return ArrayCollection<ProjetoEstabelecimento>
     */
    public function getEstabelecimentos()
    {
        return $this->estabelecimentos;
    }
    
    /**
     * @return ArrayCollection<ProjetoEstabelecimento>
     */
    public function getEstabelecimentosAtivos()
    {
        return $this->estabelecimentos->filter(function (ProjetoEstabelecimento $estabelecimento) {
            return $estabelecimento->isAtivo();
        });
    }
    
    /**
     * @param string $coCnes
     * @return boolean
     */
    public function hasEstabelecimento($coCnes)
    {
        $filter = $this->estabelecimentos->filter(function (ProjetoEstabelecimento $estabelecimento) use ($coCnes) {
            return $estabelecimento->getCoCnes() == $coCnes;
        });
        
        return !$filter->isEmpty();
    }
    
    /**
     * @param string $coCnes
     * @return Projeto
     */
    public function addEstabelecimento($coCnes)
    {
        if ($this->hasEstabelecimento($coCnes)) {
            foreach ($this->estabelecimentos as $estabelecimento) {
                if ($estabelecimento->getCoCnes() == $coCnes) {
                    $estabelecimento->ativar();
                    break;
                }
            }
            
        } else {
            $this->estabelecimentos[] = new ProjetoEstabelecimento($this, $coCnes);
        }
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getDescricaoInstituicaoEnsino()
    {
        $instituicoes = [];
        
        foreach ($this->getCampusAtivos() as $projetoCampusInstituicao) {
            $instituicoes[] = $projetoCampusInstituicao->getCampus()->getInstituicao()->getNoInstituicaoProjeto();
        }
        
        $instituicoes = array_unique($instituicoes);
        
        return implode(', ', $instituicoes);
    }
    
    /**
     * @return string
     */
    public function getDescricaoSecretariaSaude()
    {
        $secretarias = [];
        
        foreach ($this->getSecretariasAtivas() as $secretaria) {
            $secretarias[] = $secretaria->getPessoaJuridica()->getPessoa()->getNoPessoa();
        }
        
        $secretarias = array_unique($secretarias);
        
        return implode(', ', $secretarias);
    }
    
    /**
     * 
     * @return null|\AppBundle\Entity\ProjetoPessoa
     */
    public function getCoordenadorDeProjeto()
    {
        $projetoPessoa = $this->projetosPessoas->filter(function($projetoPessoa){
            return $projetoPessoa->isAtivo() && $projetoPessoa->getPessoaPerfil()->getPerfil()->isCoordenadorProjeto();
        });
        
        return ($projetoPessoa) ? $projetoPessoa->first() : null;
    }
    
    /**
     * 
     * @return null|\AppBundle\Entity\ProjetoPessoa
     */
    public function getCoordenadorDeProjetoNaoVoluntario()
    {
        $projetoPessoa = $this->projetosPessoas->filter(function($projetoPessoa){
            return $projetoPessoa->isAtivo() && 
                $projetoPessoa->getPessoaPerfil()->getPerfil()->isCoordenadorProjeto() &&
                !$projetoPessoa->isVoluntario();
        });
        
        return ($projetoPessoa) ? $projetoPessoa->first() : null;
    }
    
    /**
     * 
     * @return ArrayCollection<\AppBundle\Entity\ProjetoPessoa>
     */
    public function getParticipantesNaoVoluntarios()
    {
        return $this->projetosPessoas->filter(function($projetoPessoa){
            return !$projetoPessoa->isVoluntario() && $projetoPessoa->isAtivo();
        });
    }
    
    /**
     * 
     * @return string
     */
    public function getDescricaoCompletaProjeto()
    {
        return $this->getCoSeqProjeto() . ' - ' . $this->getNuSipar();
    }
}
