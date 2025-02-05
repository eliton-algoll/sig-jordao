<?php

namespace AppBundle\Query;

use AppBundle\Repository\PessoaPerfilRepository;
use AppBundle\Repository\TextoSaudacaoRepository;

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
     * @return \AppBundle\Entity\TextoSaudacao
     */
    public function find()
    {
        return $this->textoSaudacaoRepository->find(1);
    }
}