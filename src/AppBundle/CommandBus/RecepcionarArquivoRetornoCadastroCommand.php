<?php

namespace AppBundle\CommandBus;

use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use AppBundle\Entity\Publicacao;

final class RecepcionarArquivoRetornoCadastroCommand
{
    /**
     *
     * @var Publicacao
     * 
     * @Assert\NotBlank()
     */
    private $publicacao;
    
    /**
     *
     * @var UploadedFile
     * 
     * @Assert\NotBlank()
     * @Assert\File()
     */
    private $arquivoRetorno;
    
    /**
     * 
     * @return Publicacao
     */
    public function getPublicacao()
    {
        return $this->publicacao;
    }
        
    /**
     * 
     * @return UploadedFile
     */
    public function getArquivoRetorno()
    {
        return $this->arquivoRetorno;
    }
    
    /**
     * 
     * @param Publicacao $publicacao
     */
    public function setPublicacao(Publicacao $publicacao)
    {
        $this->publicacao = $publicacao;
    }
    
    /**
     * 
     * @param UploadedFile $arquivoRetorno
     */
    public function setArquivoRetorno(UploadedFile $arquivoRetorno)
    {
        $this->arquivoRetorno = $arquivoRetorno;
    }
    
    /**
     * 
     * @param ExecutionContextInterface $context
     * @Assert\Callback()
     */
    public function validateExtension(ExecutionContextInterface $context)
    {
        if ($this->arquivoRetorno && strtolower($this->arquivoRetorno->getClientOriginalExtension()) !== 'ret') {
            $context->buildViolation('Arquivo selecionado tem o formato diferente de “.ret” que é o formato padrão do banco para esse tipo de arquivo. Selecione novo arquivo e refaça a operação.')
                ->atPath('arquivoRetorno')
                ->addViolation();
        }
    }
}
