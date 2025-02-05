<?php

namespace AppBundle\Query;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use AppBundle\Entity\Municipio;
use AppBundle\Repository\PessoaJuridicaRepository;
use AppBundle\Validator\Constraints\Cpfcnpj;

class PessoaJuridicaQuery
{
    /**
     * @var PessoaJuridicaRepository
     */
    private $pessoaJuridicaRepository;
    
    /**
     * @var ValidatorInterface
     */
    private $validator;
    
    /**
     * @param PessoaJuridicaRepository $pessoaJuridicaRepository
     */
    public function __construct(PessoaJuridicaRepository $pessoaJuridicaRepository, ValidatorInterface $validator)
    {
        $this->pessoaJuridicaRepository = $pessoaJuridicaRepository;
        $this->validator = $validator;
    }
    
    /**
     * @param Municipio $municipio
     * @return array
     */
    public function listSecretariasSaudeByMunicipio(Municipio $municipio)
    {
        return $this->pessoaJuridicaRepository->findSecretariasSaudeByMunicipio($municipio, true);
    }
    
    /**
     * @param string $cnpj
     * @return array 
     */
    public function listSecretariaSaudeByCnpj($cnpj)
    {
        $constraint = new Cpfcnpj(array('aceitar' => 'cnpj', 'aceitar_formatado' => false));
        
        $errorList = $this->validator->validate($cnpj, $constraint);
        
        if ($errorList->count()) {
            throw new \InvalidArgumentException($errorList->get(0)->getMessage());
        }
        
        return $this->pessoaJuridicaRepository->findSecretariaSaudeByCnpj($cnpj);
    }
}
