<?php

namespace App\Query;

use App\Repository\PessoaPerfilRepository;

class PerfilQuery
{
    /**
     * @var PessoaPerfilRepository
     */
    private $pessoaPerfilRepository;
    
    /**
     * @inheritdoc
     */
    public function __construct($pessoaPerfilRepository)
    {
        $this->pessoaPerfilRepository = $pessoaPerfilRepository;
    }
    
    /**
     * @param integer $coPessoaPerfil
     * @return \App\Entity\PessoaPerfil
     */
    public function findPessoaPerfilById($coPessoaPerfil)
    {
        return $this->pessoaPerfilRepository->find($coPessoaPerfil);
    }
}