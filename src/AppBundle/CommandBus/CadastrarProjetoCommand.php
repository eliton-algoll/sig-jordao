<?php

namespace AppBundle\CommandBus;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Entity\Publicacao;
use AppBundle\Entity\PessoaJuridica;

class CadastrarProjetoCommand
{
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min = 20, max = 20)
     */
    private $nuSipar;

    /**
     * @var string
     * @Assert\Length(min = 0, max = 4000)
     */
    private $dsObservacao;

    /**
     * @var integer
     * @Assert\NotBlank()
     */
    private $publicacao;
    
    /**
     * @var integer
     * @Assert\NotBlank()
     * @Assert\Type(
     *     type="integer"
     * )
     * @Assert\Range(
     *     min = 1,
     *     max = 99999
     * )
     */
    private $qtBolsa;
    
    /**
     *
     * @var UploadedFile
     *      
     * @Assert\File(
     *      maxSize = "2M",
     *      maxSizeMessage = "Arquivo selecionado tem o formato ou o tamanho inválido. Serão aceitos arquivos com os formatos: PDF e de até 02 (dois) Megabytes. Selecione novo arquivo e refaça a operação.",
     *      mimeTypes = {"application/pdf", "application/x-pdf"},
     *      mimeTypesMessage = "Arquivo selecionado tem o formato ou o tamanho inválido. Serão aceitos arquivos com os formatos: PDF e de até 02 (dois) Megabytes. Selecione novo arquivo e refaça a operação. "
     * )
     */
    private $noDocumentoProjeto;
    
    /**
     * @var array
     * @Assert\NotBlank()
     * @Assert\Count(
     *  min="1",
     *  minMessage="Pelo menos uma área temática deve ser selecionada"
     * )
     */
    private $areasTematicas;
    
    /**
     * @var array
     * @Assert\NotBlank()
     * @Assert\Count(
     *  min="1" 
     * )
     */
    private $campus;
    
    /**
     * @var array
     * @Assert\NotBlank()
     * @Assert\Count(
     *  min="1" 
     * )
     */
    private $secretarias;
    
    /**
     * @return string
     */
    public function getNuSipar()
    {
        return $this->nuSipar;
    }

    /**
     * @return string
     */
    public function getDsObservacao()
    {
        return $this->dsObservacao;
    }

    /**
     * @param string $nuSipar
     * @return CadastrarProjetoCommand
     */
    public function setNuSipar($nuSipar)
    {
        $this->nuSipar = $nuSipar;
        return $this;
    }

    /**
     * @param string $dsObservacao
     * @return CadastrarProjetoCommand
     */
    public function setDsObservacao($dsObservacao)
    {
        $this->dsObservacao = $dsObservacao;
        return $this;
    }

    /**
     * @return integer
     */
    public function getPublicacao()
    {
        return $this->publicacao;
    }

    /**
     * @param integer $publicacao
     * @return CadastrarProjetoCommand
     */
    public function setPublicacao($publicacao)
    {
        $this->publicacao = $publicacao;
        return $this;
    }
    
    /**
     * @return integer
     */
    public function getAreasTematicas()
    {
        return $this->areasTematicas;
    }

    /**
     * @param integer $areasTematicas
     * @return CadastrarProjetoCommand
     */
    public function setAreasTematicas($areasTematicas)
    {
        $this->areasTematicas = $areasTematicas;
        return $this;
    }
    
    /**
     * @return array
     */
    public function getCampus()
    {
        return $this->campus;
    }

    /**
     * @param array $campus
     * @return CadastrarProjetoCommand
     */
    public function setCampus($campus)
    {
        $this->campus = $campus;
        return $this;
    }

    /**
     * @return array
     */
    public function getSecretarias()
    {
        return $this->secretarias;
    }

    /**
     * @param array $secretarias
     * @return CadastrarProjetoCommand
     */
    public function setSecretarias($secretarias)
    {
        $this->secretarias = $secretarias;
        return $this;
    }
    
    /**
     * @return integer
     */
    public function getQtBolsa()
    {
        return $this->qtBolsa;
    }

    /**
     * @param integer $qtBolsa
     * @return CadastrarProjetoCommand
     */
    public function setQtBolsa($qtBolsa)
    {
        $this->qtBolsa = $qtBolsa;
        return $this;
    }
    
    /**
     * 
     * @return UploadedFile
     */
    public function getNoDocumentoProjeto()
    {
        return $this->noDocumentoProjeto;
    }

    /**
     * 
     * @param UploadedFile $noDoumentoProjeto
     */
    public function setNoDocumentoProjeto(UploadedFile $noDoumentoProjeto = null)
    {
        $this->noDocumentoProjeto = $noDoumentoProjeto;
    }
}
