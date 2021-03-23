<?php

namespace AppBundle\Query;

use AppBundle\Repository\PessoaPerfilRepository;

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
     * @return \AppBundle\Entity\PessoaPerfil
     */
    public function findPessoaPerfilById($coPessoaPerfil)
    {
        return $this->pessoaPerfilRepository->find($coPessoaPerfil);
    }
}