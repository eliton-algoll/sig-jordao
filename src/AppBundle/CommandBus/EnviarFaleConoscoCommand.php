<?php

namespace AppBundle\CommandBus;

use AppBundle\Entity\TipoAssunto;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class EnviarFaleConoscoCommand
{
    /**
     *
     * @var string
     * 
     * @Assert\NotBlank( message="Este valor não deve ser vazio." )     
     */
    private $nome;
    
    /**
     *
     * @var string
     * 
     * @Assert\NotBlank( message="Este valor não deve ser vazio." )
     * @Assert\Email( message="O formato do e-mail informado é inválido. Por favor, informe um endereço de e-mail válido." )
     */
    private $email;
    
    /**
     *
     * @var string
     * 
     * @Assert\NotBlank( message="Este valor não deve ser vazio." )
     * @Assert\Email( message="O formato do e-mail informado é inválido. Por favor, informe um endereço de e-mail válido." )
     */
    private $confirmMail;
    
    /**
     *
     * @var TipoAssunto
     * 
     * @Assert\NotBlank( message="Este valor não deve ser vazio." )
     */
    private $tipoAssunto;
    
    private $assunto;
    
    /**
     *
     * @var string
     * 
     * @Assert\NotBlank( message="Este valor não deve ser vazio." )
     */
    private $menssagem;
    
    /**
     * 
     * @return string
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * 
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
    
    /**
     * 
     * @return string
     */
    public function getConfirmMail()
    {
        return $this->confirmMail;
    }
    
    /**
     * 
     * @return TipoAssunto
     */
    public function getTipoAssunto()
    {
        return $this->tipoAssunto;
    }

    /**
     * 
     * @return string
     */
    public function getAssunto()
    {
        return $this->assunto;
    }

    /**
     * 
     * @return string
     */
    public function getMenssagem()
    {
        return $this->menssagem;
    }
    
    /**
     * 
     * @param string $nome
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    /**
     * 
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }
    
    /**
     * 
     * @param string $confirmMail
     */
    public function setConfirmMail($confirmMail)
    {
        $this->confirmMail = $confirmMail;
    }
    
    /**
     * 
     * @param TipoAssunto $tipoAssunto
     */
    public function setTipoAssunto(TipoAssunto $tipoAssunto)
    {
        $this->tipoAssunto = $tipoAssunto;
    }

    /**
     * 
     * @param string $assunto
     */
    public function setAssunto($assunto)
    {
        $this->assunto = trim($assunto);
    }

    /**
     * 
     * @param string $menssagem
     */
    public function setMenssagem($menssagem)
    {
        $this->menssagem = $menssagem;
    }
            
    /**
     * @Assert\Callback
     */
    public function validateAssunto(ExecutionContextInterface $context)
    {
        if ($this->tipoAssunto && $this->tipoAssunto->isOutros() && $this->assunto == '') {
            $context->buildViolation('Este valor não deve ser vazio.')->atPath('assunto')->addViolation();
        }
    }
    
    /**
     * 
     * @param ExecutionContextInterface $context
     * 
     * @Assert\Callback
     */
    public function validateConfirmacaoEmail(ExecutionContextInterface $context)
    {
        if ($this->getEmail() !== $this->getConfirmMail()) {
            $context
                ->buildViolation('A confirmação de e-mail não é idêntica ao endereço de e-mail informado. Verifique e informe um e-mail válido.')
                ->atPath('confirmMail')
                ->addViolation();
        }
    }
}
