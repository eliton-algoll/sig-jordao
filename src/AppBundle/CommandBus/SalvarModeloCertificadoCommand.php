<?php

namespace AppBundle\CommandBus;

use AppBundle\Entity\ModeloCertificado;
use AppBundle\Entity\Programa;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class SalvarModeloCertificadoCommand
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var Programa
     *
     * @Assert\NotBlank
     */
    protected $programa;

    /**
     * @var string
     *
     * @Assert\NotBlank
     */
    protected $tipo;

    /**
     * @var string
     *
     * @Assert\NotBlank
     */
    protected $nome;

    /**
     * @var string
     *
     * @Assert\NotBlank
     */
    protected $descricao;

    /**
     * @var UploadedFile
     *
     * @Assert\NotBlank(groups={"Create"})
     * @Assert\File(
     *     maxSize = "2M",
     *     mimeTypes = {"image/jpeg"},
     *     mimeTypesMessage="É permitido anexar somente arquivos com as extensões .jpeg e .jpg."
     * )
     */
    protected $imagem;

    /**
     * @var UploadedFile
     *
     * @Assert\File(
     *     maxSize = "2M",
     *     mimeTypes = {"image/jpeg"},
     *     mimeTypesMessage="É permitido anexar somente arquivos com as extensões .jpeg e .jpg."
     * )
     */
    protected $imagemRodape;

    /**
     * @var string
     */
    protected $nomeImagem;

    /**
     * @var string
     */
    protected $nomeImagemRodape;

    /**
     * SalvarModeloCertificadoCommand constructor.
     * @param ModeloCertificado $modeloCertificado
     */
    public function __construct(ModeloCertificado $modeloCertificado = null)
    {
        if ($modeloCertificado !== null) {
            $this->setValuesFromEntity($modeloCertificado);
        }
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return Programa
     */
    public function getPrograma()
    {
        return $this->programa;
    }

    /**
     * @param Programa $programa
     */
    public function setPrograma($programa)
    {
        $this->programa = $programa;
    }

    /**
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * @param string $tipo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    /**
     * @return string
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @param string $nome
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    /**
     * @return string
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * @param string $descricao
     */
    public function setDescricao($descricao)
    {
        $this->descricao = str_replace("\r\n", "\n", $descricao);
    }

    /**
     * @return UploadedFile
     */
    public function getImagem()
    {
        return $this->imagem;
    }

    /**
     * @param UploadedFile $imagem
     */
    public function setImagem($imagem)
    {
        $this->imagem = $imagem;
    }

    /**
     * @return UploadedFile
     */
    public function getImagemRodape()
    {
        return $this->imagemRodape;
    }

    /**
     * @param UploadedFile $imagemRodape
     */
    public function setImagemRodape($imagemRodape)
    {
        $this->imagemRodape = $imagemRodape;
    }

    /**
     * @return string
     */
    public function getNomeImagem()
    {
        return $this->nomeImagem;
    }

    /**
     * @param string $nomeImagem
     */
    public function setNomeImagem($nomeImagem)
    {
        $this->nomeImagem = $nomeImagem;
    }

    /**
     * @return string
     */
    public function getNomeImagemRodape()
    {
        return $this->nomeImagemRodape;
    }

    /**
     * @param string $nomeImagemRodape
     */
    public function setNomeImagemRodape($nomeImagemRodape)
    {
        $this->nomeImagemRodape = $nomeImagemRodape;
    }

    /**
     * @param ModeloCertificado $modeloCertificado
     */
    private function setValuesFromEntity(ModeloCertificado $modeloCertificado)
    {
        $this->setId($modeloCertificado->getCoSeqModeloCertificado());
        $this->setPrograma($modeloCertificado->getPrograma());
        $this->setTipo($modeloCertificado->getTpDocumento());
        $this->setNome($modeloCertificado->getNoModeloCertificado());
        $this->setdescricao($modeloCertificado->getDsModeloCertificado());
        $this->setNomeImagem($modeloCertificado->getNoImagemCertificado());
        $this->setNomeImagemRodape($modeloCertificado->getNoImagemRodape());
    }

}