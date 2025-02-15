<?php

namespace App\CommandBus;

use App\Entity\FormularioAvaliacaoAtividade;
use App\Entity\Periodicidade;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class CadastrarFormularioAvaliacaoAtividadeCommand
{
    /**
     *
     * @var FormularioAvaliacaoAtividade 
     */
    private $formularioAvaliacaoAtividade;
    
    /**
     *
     * @var string
     * 
     * @Assert\NotBlank()
     * @Assert\Length( max = 100 )
     */
    private $titulo;
    
    /**
     *
     * @var string
     * 
     * @Assert\Length( max = 2000 )
     */
    private $descricao;
    
    /**
     *
     * @var Periodicidade
     * 
     * @Assert\NotBlank() 
     */
    private $periodicidade;
    
    /**
     *
     * @var ArrayCollection<\App\Entity\Perfil>
     * 
     * @Assert\NotBlank()
     */
    private $perfis;
    
    /**
     *
     * @var string
     * 
     * @Assert\Length( max = 200 )
     * @Assert\Url()
     */
    private $urlFormulario;
    
    /**
     *
     * @var UploadedFile
     * 
     * @Assert\File(
     *      maxSize = "2M",
     *      mimeTypes = {
     *          "application/pdf", "application/x-pdf", "application/msword", 
     *          "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
     *          "application/vnd.ms-word.document.macroEnabled.12", "application/vnd.ms-excel",
     *          "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
     *      },
     *      mimeTypesMessage = "Arquivo selecionado tem o formato ou o tamanho inválido. Serão aceitos arquivos com os formatos: DOC, DOCX, DOCM, PDF, XLS e XLSX e de até 02 (dois) Megabytes. Selecione novo arquivo e refaça a operação."
     * )
     */
    private $fileFormulario;
    
    /**
     * 
     * @return FormularioAvaliacaoAtividade
     */
    public function getFormularioAvaliacaoAtividade()
    {
        return $this->formularioAvaliacaoAtividade;
    }
        
    /**
     * 
     * @return string
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * 
     * @return string
     */
    public function getDescricao()
    {
        return $this->descricao;
    }
    
    /**
     * 
     * @return Periodicidade
     */
    public function getPeriodicidade()
    {
        return $this->periodicidade;
    }
    
    /**
     * 
     * @return ArrayCollection<\App\Entity\Perfil>
     */
    public function getPerfis()
    {
        return $this->perfis;
    }

    /**
     * 
     * @return string
     */
    public function getUrlFormulario()
    {
        return $this->urlFormulario;
    }

    /**
     * 
     * @return UploadedFile
     */
    public function getFileFormulario()
    {
        return $this->fileFormulario;
    }
    
    /**
     * 
     * @param FormularioAvaliacaoAtividade $formularioAvaliacaoAtividade
     */
    public function setFormularioAvaliacaoAtividade(FormularioAvaliacaoAtividade $formularioAvaliacaoAtividade)
    {
        $this->formularioAvaliacaoAtividade = $formularioAvaliacaoAtividade;
        $this->setTitulo($formularioAvaliacaoAtividade->getNoFormulario());
        $this->setDescricao($formularioAvaliacaoAtividade->getDsAvaliacao());        
        $this->setPeriodicidade($formularioAvaliacaoAtividade->getPeriodicidade());
        $this->setUrlFormulario($formularioAvaliacaoAtividade->getDsUrlFormulario());        
        $this->setPerfis(
            $formularioAvaliacaoAtividade
                ->getPerfilFormularioAvaliacaoAtividadeAtivos()
                ->map(function ($perfilFormularioAvaliacaoAtividade) {
                    return $perfilFormularioAvaliacaoAtividade->getPerfil();
                })
        );
    }
    
    /**
     * 
     * @param string $titulo
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
    }

    /**
     * 
     * @param string $descricao
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }
    
    /**
     * 
     * @param Periodicidade $periodicidade
     */
    public function setPeriodicidade(Periodicidade $periodicidade)
    {
        $this->periodicidade = $periodicidade;
    }
    
    /**
     * 
     * @param ArrayCollection $perfis
     */
    public function setPerfis($perfis)
    {
        $this->perfis = $perfis;
    }

    /**
     * 
     * @param string $urlFormulario
     */
    public function setUrlFormulario($urlFormulario)
    {
        $this->urlFormulario = $urlFormulario;
    }

    /**
     * 
     * @param UploadedFile $fileFormulario
     */
    public function setFileFormulario(UploadedFile $fileFormulario)
    {
        $this->fileFormulario = $fileFormulario;
    }
    
    /**
     * 
     * @param ExecutionContextInterface $context
     *
     */
    public function validateFileFormulario(ExecutionContextInterface $context)
    {
        if (!$this->getFormularioAvaliacaoAtividade() && !$this->getFileFormulario()) {
            $context->buildViolation('Este valor não deve ser vazio')
                ->atPath('fileFormulario')
                ->addViolation();
        }
    }
    
    /**
     * 
     * @param ExecutionContextInterface $context
     * 
     * @Assert\Callback
     */
    public function validatePerfis(ExecutionContextInterface $context)
    {
        if (!$this->perfis || $this->perfis->isEmpty()) {
            $context->buildViolation('Este valor não deve ser vazio')
                ->atPath('perfis')
                ->addViolation();
        }
    }
}
