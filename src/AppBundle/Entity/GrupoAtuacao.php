<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * GrupoAtuacao
 *
 * @ORM\Table(name="DBPET.TB_GRUPO_ATUACAO")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GrupoAtuacaoRepository")
 */
class GrupoAtuacao extends AbstractEntity
{
    use \AppBundle\Traits\DeleteLogicoTrait;
    use \AppBundle\Traits\DataInclusaoTrait;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\Column(name="CO_SEQ_GRUPO_ATUACAO", type="integer", unique=true)
     * @ORM\SequenceGenerator(sequenceName="DBPET.SQ_GRUPOATUACAO_COSEQGRPATUAC", allocationSize=1, initialValue=1)
     */
    private $coSeqGrupoAtuacao;

    /**
     * @var ArrayCollection<AreaTematicaGrupoAtuacao>
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\AreaTematicaGrupoAtuacao", mappedBy="grupoAtuacao", cascade={"persist"})
     */
    private $areasTematicasGruposAtuacao;

    /**
     * @var ArrayCollection<ProjetoPessoaGrupoAtuacao>
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ProjetoPessoaGrupoAtuacao", mappedBy="grupoAtuacao")
     */
    private $projetoPessoaGrupoAtuacao;

    /**
     * @var string
     *
     * @ORM\Column(name="NO_GRUPO_ATUACAO", type="string")
     */
    private $noGrupoAtuacao;

    /**
     * @var Projeto
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Projeto")
     * @ORM\JoinColumn(name="CO_PROJETO", referencedColumnName="CO_SEQ_PROJETO")
     */
    private $projeto;

    /**
     * @var string
     *
     * @ORM\Column(name="ST_CONFIRMACAO", type="string", length=1, nullable=false)
     */
    private $stConfirmacao;

    /**
     * @var string coEixoAtuacao
     *
     * @ORM\Column(name="CO_EIXO_ATUACAO", type="string", length=1, nullable=true)
     */
    private $coEixoAtuacao;

    /**
     * @var string
     * @ORM\Column(name="DS_TEMA_ABORDADO", type="string", length=255, nullable=false)
     */
    private $dsTemaAbordado;

    /**
     * @inheritdoc
     */
    public function __construct()
    {
        $this->stRegistroAtivo = 'S';
        $this->dtInclusao = new \DateTime();
        $this->projetoPessoaGrupoAtuacao = new ArrayCollection();
        $this->areasTematicasGruposAtuacao = new ArrayCollection();

        $this->desconfirmar();
    }

    /**
     * Get coSeqGrupoAtuacao
     *
     * @return int
     */
    public function getCoSeqGrupoAtuacao()
    {
        return $this->coSeqGrupoAtuacao;
    }

    /**
     * Get areasTematicasGruposAtuacao
     *
     * @return ArrayCollection<AreaTematicaGrupoAtuacao>
     */
    public function getAreasTematicasGruposAtuacao()
    {
        return $this->areasTematicasGruposAtuacao;
    }

    /**
     * Get projetoPessoaGrupoAtuacao
     *
     * @return ArrayCollection<ProjetoPessoaGrupoAtuacao>
     */
    public function getProjetoPessoaGrupoAtuacao()
    {
        return $this->projetoPessoaGrupoAtuacao;
    }

    /**
     * Get projetoPessoaGrupoAtuacao
     *
     * @return ArrayCollection<ProjetoPessoaGrupoAtuacao>
     */
    public function getProjetoPessoaGrupoAtuacaoAtivas()
    {
        return $this->projetoPessoaGrupoAtuacao->filter(function (ProjetoPessoaGrupoAtuacao $item) {
            return $item->isAtivo() && $item->getProjetoPessoa()->isAtivo();
        });
    }

    /**
     * @return ArrayCollection<ProjetoPessoaGrupoAtuacao>
     */
    public function getProjetoPessoaGrupoAtuacaoBolsistas()
    {
        return $this->getProjetoPessoaGrupoAtuacaoAtivas()->filter(function (ProjetoPessoaGrupoAtuacao $projetoPessoaGrupoAtuacao) {
            return !$projetoPessoaGrupoAtuacao->getProjetoPessoa()->isVoluntario();
        });
    }

    /**
     * Get noGrupoAtuacao
     * @return string
     */
    public function getNoGrupoAtuacao()
    {
        return $this->noGrupoAtuacao;
    }

    /**
     * @return Projeto
     */
    public function getProjeto()
    {
        return $this->projeto;
    }

    /**
     * gera nome do grupo
     */
    private function setNoGrupoAtuacao()
    {
        $nomes = [];

        foreach ($this->getAreasTematicasGruposAtuacaoAtivas() as $item) {
            $nomes[] = $item->getAreaTematica()->getTipoAreaTematica()->getDsTipoAreaTematica();
        }

        $this->noGrupoAtuacao = implode(', ', $nomes);
    }

    private function setNoGrupoAtuacaoDes($noGrupoAtuacao)
    {
        $this->noGrupoAtuacao = $noGrupoAtuacao;
    }

    /**
     * @param string $coEixoAtuacao
     * @return GrupoAtuacao
     */
    public function setCoEixoAtuacao($coEixoAtuacao)
    {
        $this->coEixoAtuacao = $coEixoAtuacao;
        return $this;
    }

    /**
     * @return string
     */
    public function getCoEixoAtuacao()
    {
        return $this->coEixoAtuacao;
    }

    /**
     * @param string $dsTemaAbordado
     * @return GrupoAtuacao
     */
    public function setDsTemaAbordado($dsTemaAbordado)
    {
        $this->dsTemaAbordado = $dsTemaAbordado;
        return $this;
    }

    /**
     * @return string
     */
    public function getDsTemaAbordado()
    {
        return $this->dsTemaAbordado;
    }

    /**
     * @param AreaTematica $areaTematica
     * @return GrupoAtuacao
     */
    public function addAreaTematica(AreaTematica $areaTematica)
    {
        $this->areasTematicasGruposAtuacao[] = new AreaTematicaGrupoAtuacao($this, $areaTematica);
        $this->setNoGrupoAtuacao();
        return $this;
    }

    /**
     * @return ArrayCollection<AreaTematicaGrupoAtuacao>
     */
    public function getAreasTematicasGruposAtuacaoAtivas()
    {
        return $this->areasTematicasGruposAtuacao->filter(function ($areaGrupo) {
            return $areaGrupo->isAtivo();
        });
    }

    /**
     * @return string
     */
    public function getDescricaoAreasTematicas()
    {
        $tipos = array();

        foreach ($this->getAreasTematicasGruposAtuacaoAtivas() as $areaGrupo) {
            $tipos[] = $areaGrupo->getAreaTematica()->getTipoAreaTematica()->getDsTipoAreaTematica();
        }

        return implode(', ', $tipos);
    }

    /**
     * sobrescrita da deleção lógica da trait
     * @return GrupoAtuacao
     */
    public function inativar()
    {
        $participantes = $this->projetoPessoaGrupoAtuacao->filter(function ($item) {
            return $item->isAtivo();
        });

        if (!$participantes->isEmpty()) {
            throw new \DomainException('Não é possível inativar um grupo que possui participantes.');
        }

        $this->stRegistroAtivo = 'N';

        return $this;
    }

    /**
     * @param ProjetoPessoa $projetoPessoa
     * @return boolean
     */
    public function isParticipanteVinculado(ProjetoPessoa $projetoPessoa)
    {
        foreach ($this->getProjetoPessoaGrupoAtuacaoAtivas() as $item) {
            if ($item->getProjetoPessoa() == $projetoPessoa) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return integer
     */
    public function qtdEstudantesBolsistas()
    {
        return $this->projetoPessoaGrupoAtuacao->filter(function ($projetoPessoaGrupoAtuacao) {
            return $projetoPessoaGrupoAtuacao->getProjetoPessoa()->getPessoaPerfil()->getPerfil()->isEstudante() &&
                !$projetoPessoaGrupoAtuacao->getProjetoPessoa()->isVoluntario() &&
                $projetoPessoaGrupoAtuacao->isAtivo();
        })->count();
    }

    /**
     * @return integer
     */
    public function qtdTutoresBolsistas()
    {
        return $this->projetoPessoaGrupoAtuacao->filter(function ($projetoPessoaGrupoAtuacao) {
            return $projetoPessoaGrupoAtuacao->getProjetoPessoa()->getPessoaPerfil()->getPerfil()->isTutor() &&
                !$projetoPessoaGrupoAtuacao->getProjetoPessoa()->isVoluntario() &&
                $projetoPessoaGrupoAtuacao->isAtivo();
        })->count();
    }

    /**
     * @return integer
     */
    public function qtdPreceptoresBolsistas()
    {
        return $this->projetoPessoaGrupoAtuacao->filter(function ($projetoPessoaGrupoAtuacao) {
            return $projetoPessoaGrupoAtuacao->getProjetoPessoa()->getPessoaPerfil()->getPerfil()->isPreceptor() &&
                !$projetoPessoaGrupoAtuacao->getProjetoPessoa()->isVoluntario() &&
                $projetoPessoaGrupoAtuacao->isAtivo();
        })->count();
    }

    /**
     * @return integer
     */
    public function qtdCoordenadoresGrupoBolsistas()
    {
        return $this->projetoPessoaGrupoAtuacao->filter(function ($projetoPessoaGrupoAtuacao) {
            return $projetoPessoaGrupoAtuacao->getProjetoPessoa()->getPessoaPerfil()->getPerfil()->isCoordenadorGrupo() &&
                !$projetoPessoaGrupoAtuacao->getProjetoPessoa()->isVoluntario() &&
                $projetoPessoaGrupoAtuacao->isAtivo();
        })->count();
    }

    /**
     * @return ArrayCollection<ProjetoPessoaGrupoAtuacao>
     */
    public function getEstudantesBolsistas()
    {
        return $this->projetoPessoaGrupoAtuacao->filter(function ($projetoPessoaGrupoAtuacao) {
            return $projetoPessoaGrupoAtuacao->getProjetoPessoa()->getPessoaPerfil()->getPerfil()->isEstudante() &&
                !$projetoPessoaGrupoAtuacao->getProjetoPessoa()->isVoluntario() &&
                $projetoPessoaGrupoAtuacao->isAtivo();
        });
    }

    /**
     * @return ArrayCollection<ProjetoPessoaGrupoAtuacao>
     */
    public function getPreceptoresBolsistas()
    {
        return $this->projetoPessoaGrupoAtuacao->filter(function ($projetoPessoaGrupoAtuacao) {
            return $projetoPessoaGrupoAtuacao->getProjetoPessoa()->getPessoaPerfil()->getPerfil()->isPreceptor() &&
                !$projetoPessoaGrupoAtuacao->getProjetoPessoa()->isVoluntario() &&
                $projetoPessoaGrupoAtuacao->isAtivo();
        });
    }

    /**
     * @return ArrayCollection<ProjetoPessoaGrupoAtuacao>
     */
    public function getTutoresBolsistas()
    {
        return $this->projetoPessoaGrupoAtuacao->filter(function ($projetoPessoaGrupoAtuacao) {
            return $projetoPessoaGrupoAtuacao->getProjetoPessoa()->getPessoaPerfil()->getPerfil()->isTutor() &&
                !$projetoPessoaGrupoAtuacao->getProjetoPessoa()->isVoluntario() &&
                $projetoPessoaGrupoAtuacao->isAtivo();
        });
    }

    /**
     * @return ArrayCollection<ProjetoPessoaGrupoAtuacao>
     */
    public function getCoordenadoresGrupoBolsistas()
    {
        return $this->projetoPessoaGrupoAtuacao->filter(function ($projetoPessoaGrupoAtuacao) {
            return $projetoPessoaGrupoAtuacao->getProjetoPessoa()->getPessoaPerfil()->getPerfil()->isCoordenadorGrupo() &&
                !$projetoPessoaGrupoAtuacao->getProjetoPessoa()->isVoluntario() &&
                $projetoPessoaGrupoAtuacao->isAtivo();
        });
    }

    public function confirmar()
    {
        $this->stConfirmacao = 'S';
    }

    public function desconfirmar()
    {
        $this->stConfirmacao = 'N';
    }

    /**
     * @return bool
     */
    public function isConfirmado()
    {
        return 'S' === $this->stConfirmacao;
    }

    /**
     * @return string
     */
    public function getDescricaoConfirmacao()
    {
        return 'S' === $this->stConfirmacao ? 'Confirmado' : 'A confirmar';
    }

    /**
     * @return array
     */
    public function getTemasAbordados() {
        $temas = array();

        if (!is_null($this->coEixoAtuacao)) {
            switch ($this->coEixoAtuacao) {
                case 'G': // Gestão em Saúde
                    $temas[] = array(
                        'id' => 1,
                        'valor' => 'Gestão do Trabalho em Saúde'
                    );
                    $temas[] = array(
                        'id' => 2,
                        'valor' => 'Gestão de Sistemas e Serviços de Saúde'
                    );
                    $temas[] = array(
                        'id' => 3,
                        'valor' => 'Modelos de Atenção à Saúde'
                    );
                    $temas[] = array(
                        'id' => 4,
                        'valor' => 'Redes de Atenção à Saúde'
                    );
                    $temas[] = array(
                        'id' => 5,
                        'valor' => 'Ensino na Saúde'
                    );
                    $temas[] = array(
                        'id' => 6,
                        'valor' => 'Gestão das Práticas de Educação em Saúde'
                    );
                    $temas[] = array(
                        'id' => 7,
                        'valor' => 'Economia da Saúde'
                    );
                    $temas[] = array(
                        'id' => 8,
                        'valor' => 'Avaliação Econômica em Saúde'
                    );
                    $temas[] = array(
                        'id' => 9,
                        'valor' => 'Gestão de Custos em Instituições de Saúde'
                    );
                    $temas[] = array(
                        'id' => 10,
                        'valor' => 'Judicialização da Saúde sob o Enfoque da Evidência Científica'
                    );
                    $temas[] = array(
                        'id' => 11,
                        'valor' => 'Avaliação e Monitoramento em Saúde'
                    );
                    $temas[] = array(
                        'id' => 12,
                        'valor' => 'Organização de Serviços de Saúde e Mudanças no Modelo de Atenção à Saúde'
                    );
                    $temas[] = array(
                        'id' => 13,
                        'valor' => 'Novas Tecnologias de Informação e Comunicação em Saúde'
                    );
                    $temas[] = array(
                        'id' => 14,
                        'valor' => 'Disponibilidade e Oferta de Serviços (equipamentos/insumos/medicamentos)'
                    );
                    $temas[] = array(
                        'id' => 15,
                        'valor' => 'Infraestrutura (estrutura física/recursos humanos/integração ensino-saúde)'
                    );
                    $temas[] = array(
                        'id' => 16,
                        'valor' => 'Epidemiologia'
                    );
                    $temas[] = array(
                        'id' => 17,
                        'valor' => 'Iniciativas de Atenção à Saúde, de Gestão em Saúde e de Educação em Saúde Voltadas para a Pandemia de Covid-19, nos três níveis de Atenção à Saúde'
                    );
                    break;
                case 'A': // Assistência à Saúde
                    $temas[] = array(
                        'id' => 18,
                        'valor' => 'Vigilância em Saúde e Promoção da Saúde'
                    );
                    $temas[] = array(
                        'id' => 19,
                        'valor' => 'Procedimentos na Atenção Primária à Saúde'
                    );
                    $temas[] = array(
                        'id' => 20,
                        'valor' => 'Atenção Domiciliar do SUS'
                    );
                    $temas[] = array(
                        'id' => 21,
                        'valor' => 'Atenção Ambulatorial Especializada e Hospitalar do SUS'
                    );
                    $temas[] = array(
                        'id' => 22,
                        'valor' => 'Segurança do Paciente'
                    );
                    $temas[] = array(
                        'id' => 23,
                        'valor' => 'Urgência e Emergência do SUS'
                    );
                    $temas[] = array(
                        'id' => 24,
                        'valor' => 'Os Cuidados com a Saúde Mental'
                    );
                    $temas[] = array(
                        'id' => 25,
                        'valor' => 'As Doenças Crônicas'
                    );
                    $temas[] = array(
                        'id' => 26,
                        'valor' => 'Atenção às Demandas e aos Processos Eletivos'
                    );
                    $temas[] = array(
                        'id' => 27,
                        'valor' => 'Iniciativas de Atenção à Saúde, de Gestão em Saúde e de Educação em Saúde Voltadas para a Pandemia de Covid-19, nos três níveis de Atenção à Saúde'
                    );
                    break;
            }
        }

        return $temas;
    }

}