<?php

namespace App\CommandBus;

use App\Entity\TramitacaoFormulario;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

final class CadastrarRetornoFormularioAvaliacaoAtividadeCommand
{
    /**
     *
     * @var TramitacaoFormulario
     */
    private $tramitacaoFormulario;
    
    /**
     *
     * @var string
     */
    private $noFormulario;
    
    /**
     *
     * @var string
     * 
     * @Assert\Regex(
     *      pattern = "/\d{20}| /"     
     * )
     */
    private $nuProtocoloFormSUS;
    
    /**
     *
     * @var UploadedFile
     * 
     * @Assert\NotBlank()
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
     * @param TramitacaoFormulario $tramitacaoFormulario
     */
    public function __construct(TramitacaoFormulario $tramitacaoFormulario)
    {
        $this->tramitacaoFormulario = $tramitacaoFormulario;
        $this->noFormulario = $tramitacaoFormulario
            ->getEnvioFormularioAvaliacaoAtividade()
            ->getFormularioAvaliacaoAtividade()
            ->getNoFormulario();
    }
    
    /**
     * 
     * @return TramitacaoFormulario
     */
    public function getTramitacaoFormulario()
    {
        return $this->tramitacaoFormulario;
    }
        
    /**
     * 
     * @return string
     */
    public function getNoFormulario()
    {
        return $this->noFormulario;
    }

    /**
     * 
     * @return string
     */
    public function getNuProtocoloFormSUS()
    {
        return $this->nuProtocoloFormSUS;
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
     * @param string $noFormulario
     */
    public function setNoFormulario($noFormulario)
    {
        $this->noFormulario = $noFormulario;
    }

    /**
     * 
     * @param string $nuProtocoloFormSUS
     */
    public function setNuProtocoloFormSUS($nuProtocoloFormSUS)
    {
        $this->nuProtocoloFormSUS = $nuProtocoloFormSUS;
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
     */
    public function valideProtocoFormSUS(ExecutionContextInterface $context)
    {
        if ($this->getTramitacaoFormulario()
                ->getEnvioFormularioAvaliacaoAtividade()
                ->getFormularioAvaliacaoAtividade()
                ->getDsUrlFormulario() &&
            strlen(trim($this->getNuProtocoloFormSUS())) != 20
        ) {
            $context->buildViolation('Este valor não deve ser vazio')
                ->atPath('nuProtocoloFormSUS')
                ->addViolation();
        }
    }
}
