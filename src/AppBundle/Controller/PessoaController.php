<?php

namespace AppBundle\Controller;

use AppBundle\Controller\ControllerAbstract;
use AppBundle\Entity\Pessoa;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\NoResultException;

class PessoaController extends ControllerAbstract
{
    
    /**
     * @Route("/pessoa/{cpf}", name="pessoa_get_by_cpf", options={"expose"=true}))
     */
    public function getByCpfAction(Request $request, $cpf)
    {
        try {
            $data = $this->get('app.pessoa_fisica_query')->getByCpf($cpf);
        } catch(NoResultException $e) {
            $data = null;
        }
        return new JsonResponse($data);
    }
    
    /**
     * 
     * @param Pessoa $pessoa
     * 
     * @Route("/pessoa/telefones/{pessoa}", name="pessoa_get_telefones", options={"expose"=true})     
     */
    public function getPessoaTelefonesAction(Pessoa $pessoa)
    {
        $telefones = $pessoa->getTelefonesAtivos();                
        
        if (!$telefones->isEmpty()) {
            $telefones = $telefones->map(function($telefone){
                return $telefone->__toArray();
            });
        }        
        
        return new JsonResponse($telefones->toArray());
    }
    
    /**
     * 
     * @param Pessoa $pessoa
     * 
     * @Route("/pessoa/email/{pessoa}", name="pessoa_get_email", options={"expose"=true})     
     */
    public function getPessoaEmailAction(Pessoa $pessoa)
    {
        $email = $pessoa->getEnderecoWebAtivo();
        
        if ($email) {
            $email = $email->__toArray();
        }
        
        return new JsonResponse($email);
    }
}
