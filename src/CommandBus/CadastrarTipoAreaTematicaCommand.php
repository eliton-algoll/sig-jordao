<?php

namespace App\CommandBus;

use App\Entity\Instituicao;
use App\Entity\Projeto;
use App\Entity\TipoAreaTematica;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Publicacao;
use App\Entity\PessoaJuridica;

class CadastrarTipoAreaTematicaCommand
{

    /**
     * @var TipoAreaTematica
     */
    private $tipoAreaTematica;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min = 0, max = 100)
     */
    private $dsTipoAreaTematica;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $tpAreaTematica;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $tpAreaFormacao;

    /**
     * @return TipoAreaTematica
     */
    public function getTipoAreaTematica()
    {
        return $this->tipoAreaTematica;
    }

    /**
     * @return string
     */
    public function getDsTipoAreaTematica()
    {
        return $this->dsTipoAreaTematica;
    }

    /**
     * @param string $dsTipoAreaTematica
     * @return CadastrarTipoAreaTematicaCommand
     */
    public function setDsTipoAreaTematica($dsTipoAreaTematica)
    {
        $this->dsTipoAreaTematica = $dsTipoAreaTematica;
        return $this;
    }

    /**
     * @return string
     */
    public function getTpAreaTematica()
    {
        return $this->tpAreaTematica;
    }

    /**
     * @param string $tpAreaTematica
     * @return CadastrarTipoAreaTematicaCommand
     */
    public function setTpAreaTematica($tpAreaTematica)
    {
        $this->tpAreaTematica = $tpAreaTematica;
        return $this;
    }

    /**
     * @return string
     */
    public function getTpAreaFormacao()
    {
        return $this->tpAreaFormacao;
    }

    /**
     * @param string $tpAreaFormacao
     * @return CadastrarTipoAreaTematicaCommand
     */
    public function setTpAreaFormacao($tpAreaFormacao)
    {
        $this->tpAreaFormacao = $tpAreaFormacao;
        return $this;
    }


    /**
    * @param TipoAreaTematica $tipoAreaTematica
    */
    public function setValuesByEntity(TipoAreaTematica $tipoAreaTematica)
    {
        $this->tpAreaTematica     = $tipoAreaTematica->getTpAreaTematica();
        $this->tpAreaFormacao     = $tipoAreaTematica->getTpAreaFormacao();
        $this->dsTipoAreaTematica = $tipoAreaTematica->getDsTipoAreaTematica();
        $this->tipoAreaTematica   = $tipoAreaTematica;
    }

}
