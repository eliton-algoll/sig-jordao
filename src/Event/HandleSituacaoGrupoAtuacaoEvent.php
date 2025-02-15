<?php

namespace App\Event;

use App\Entity\ProjetoPessoa;
use Symfony\Component\EventDispatcher\Event;

final class HandleSituacaoGrupoAtuacaoEvent extends Event
{

    const NAME = 'handle_situacao.grupo_atuacao';

    /**
     * @var ProjetoPessoa
     */
    private $projetoPessoa;

    /**
     * HandleSituacaoGrupoAtuacaoEvent constructor.
     * @param ProjetoPessoa $projetoPessoa
     */
    public function __construct(ProjetoPessoa $projetoPessoa)
    {
        $this->projetoPessoa = $projetoPessoa;
    }

    /**
     * @return ProjetoPessoa
     */
    public function getProjetoPessoa()
    {
        return $this->projetoPessoa;
    }

}