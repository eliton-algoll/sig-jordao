<?php

namespace App\Query;

use App\Repository\PessoaPerfilRepository;
use App\Repository\TextoSaudacaoRepository;

class TextoSaudacaoQuery
{
    /**
     * @var TextoSaudacaoRepository
     */
    private $textoSaudacaoRepository;
    
    /**
     * @inheritdoc
     */
    public function __construct(TextoSaudacaoRepository $textoSaudacaoRepository)
    {
        $this->textoSaudacaoRepository = $textoSaudacaoRepository;
    }
    
    /**
     * @return \App\Entity\TextoSaudacao
     */
    public function find()
    {
        return $this->textoSaudacaoRepository->find(1);
    }
}