<?php

namespace AppBundle\Query;

class PessoaFisicaQuery
{
    /**
     * @var PessoaFisicaRepository
     */
    protected $repository;

    /**
     * @param PessoaFisicaRepository $repository
     */
    public function __construct($repository)
    {
        $this->repository = $repository;
    }
    
    public function getByCpf($cpf) 
    {
        return $this->repository->getByCpf($cpf);
    }
}