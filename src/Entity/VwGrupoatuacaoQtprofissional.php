<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VwGrupoatuacaoQtprofissional
 *
 * @ORM\Table(name="DBPET.VW_GRUPOATUACAO_QTPROFISSIONAL")
 * @ORM\Entity(repositoryClass="App\Repository\VwGrupoatuacaoQtprofissionalRepository")
 */
class VwGrupoatuacaoQtprofissional extends AbstractEntity
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="CO_SEQ_PROJETO", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
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
     * @ORM\Column(name="CO_SEQ_GRUPO_ATUACAO", type="integer")
     */
    private $coSeqGrupoAtuacao;

    /**
     * @var string
     *
     * @ORM\Column(name="NO_GRUPO_ATUACAO", type="text")
     */
    private $noGrupoAtuacao;

    /**
     * @var string
     *
     * @ORM\Column(name="ST_VOLUNTARIO_PROJETO", type="string", length=3)
     */
    private $stVoluntarioProjeto;

    /**
     * @var int
     *
     * @ORM\Column(name="ADMINISTRADOR", type="integer")
     */
    private $administrador;

    /**
     * @var int
     *
     * @ORM\Column(name="COORD_PROJETO", type="integer")
     */
    private $coordProjeto;

    /**
     * @var string
     *
     * @ORM\Column(name="COORD_GRUPO", type="integer")
     */
    private $coordGrupo;

    /**
     * @var int
     *
     * @ORM\Column(name="PRECEPTOR", type="integer")
     */
    private $preceptor;

    /**
     * @var string
     *
     * @ORM\Column(name="TUTOR", type="integer")
     */
    private $tutor;

    /**
     * @var int
     *
     * @ORM\Column(name="ESTUDANTE", type="integer")
     */
    private $estudante;


    /**
     * Set coSeqProjeto
     *
     * @param integer $coSeqProjeto
     *
     * @return VwGrupoatuacaoQtprofissional
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
     * @return VwGrupoatuacaoQtprofissional
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
     * Set coSeqGrupoAtuacao
     *
     * @param integer $coSeqGrupoAtuacao
     *
     * @return VwGrupoatuacaoQtprofissional
     */
    public function setCoSeqGrupoAtuacao($coSeqGrupoAtuacao)
    {
        $this->coSeqGrupoAtuacao = $coSeqGrupoAtuacao;

        return $this;
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
     * Set noGrupoAtuacao
     *
     * @param string $noGrupoAtuacao
     *
     * @return VwGrupoatuacaoQtprofissional
     */
    public function setNoGrupoAtuacao($noGrupoAtuacao)
    {
        $this->noGrupoAtuacao = $noGrupoAtuacao;

        return $this;
    }

    /**
     * Get noGrupoAtuacao
     *
     * @return string
     */
    public function getNoGrupoAtuacao()
    {
        return $this->noGrupoAtuacao;
    }

    /**
     * Set stVoluntarioProjeto
     *
     * @param string $stVoluntarioProjeto
     *
     * @return VwGrupoatuacaoQtprofissional
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
     * Set administrador
     *
     * @param integer $administrador
     *
     * @return VwGrupoatuacaoQtprofissional
     */
    public function setAdministrador($administrador)
    {
        $this->administrador = $administrador;

        return $this;
    }

    /**
     * Get administrador
     *
     * @return int
     */
    public function getAdministrador()
    {
        return $this->administrador;
    }

    /**
     * Set coordProjeto
     *
     * @param integer $coordProjeto
     *
     * @return VwGrupoatuacaoQtprofissional
     */
    public function setCoordProjeto($coordProjeto)
    {
        $this->coordProjeto = $coordProjeto;

        return $this;
    }

    /**
     * Get coordProjeto
     *
     * @return int
     */
    public function getCoordProjeto()
    {
        return $this->coordProjeto;
    }

    /**
     * Set coordGrupo
     *
     * @param string $coordGrupo
     *
     * @return VwGrupoatuacaoQtprofissional
     */
    public function setCoordGrupo($coordGrupo)
    {
        $this->coordGrupo = $coordGrupo;

        return $this;
    }

    /**
     * Get coordGrupo
     *
     * @return string
     */
    public function getCoordGrupo()
    {
        return $this->coordGrupo;
    }

    /**
     * Set preceptor
     *
     * @param integer $preceptor
     *
     * @return VwGrupoatuacaoQtprofissional
     */
    public function setPreceptor($preceptor)
    {
        $this->preceptor = $preceptor;

        return $this;
    }

    /**
     * Get preceptor
     *
     * @return int
     */
    public function getPreceptor()
    {
        return $this->preceptor;
    }

    /**
     * Set tutor
     *
     * @param string $tutor
     *
     * @return VwGrupoatuacaoQtprofissional
     */
    public function setTutor($tutor)
    {
        $this->tutor = $tutor;

        return $this;
    }

    /**
     * Get tutor
     *
     * @return string
     */
    public function getTutor()
    {
        return $this->tutor;
    }

    /**
     * Set estudante
     *
     * @param integer $estudante
     *
     * @return VwGrupoatuacaoQtprofissional
     */
    public function setEstudante($estudante)
    {
        $this->estudante = $estudante;

        return $this;
    }

    /**
     * Get estudante
     *
     * @return int
     */
    public function getEstudante()
    {
        return $this->estudante;
    }
}

