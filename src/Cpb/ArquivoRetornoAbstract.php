<?php

namespace App\Cpb;

use Symfony\Component\Validator\Validator\RecursiveValidator;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Cpb\Exception\EstruturaArquivoInvalidaException;
use App\Cpb\Exception\ArquivoNotLoadedException;
use App\Cpb\Exception\ArquivoLoadedException;

abstract class ArquivoRetornoAbstract implements ArquivoInterface
{
    const ROW_SIZE = 240;

    /**
     *
     * @var RecursiveValidator 
     */
    protected $validator;    
    
    /**
     *
     * @var mixed 
     * 
     * @Assert\NotBlank( message = "O arquivo selecionado tem seu primeiro registro com classificação diferente de “1 – Header (cabeçalho)”, sendo essa informação obrigatória para o arquivo de retorno. Selecione novo arquivo e refaça a operação." )
     * @Assert\Valid()
     */
    private $header;
    
    /**
     *
     * @var array
     * 
     * @Assert\NotBlank( message = "O arquivo selecionado não possui nenhum registro com classificação “2 – DETALHE”, ou seja, não possui nenhum participante relacionado. Selecione novo arquivo e refaça a operação." )
     * @Assert\Valid()
     */
    private $detalhes = array();
    
    /**
     *
     * @var mixed 
     * 
     * @Assert\NotBlank( message = "O arquivo selecionado tem seu último registro com classificação diferente de “3 – TRAILER (rodapé)”, sendo essa informação obrigatória para o arquivo de retorno. Selecione novo arquivo e refaça a operação." )
     * @Assert\Valid()
     */
    private $trailer;
    
    /**
     *
     * @var boolean
     */
    protected $isLoaded = false;
    
    /**
     *
     * @var ConstraintViolationList 
     */
    protected $errors;
    
    /**
     * O método deverá retonar o nome da classe que será utilizada como Header do arquivo
     * 
     * @return string
     */
    abstract protected function getHeaderClass();
    
    /**
     * O método deverá retornar o nome da classe que será utilizada como Detalha do arquivo
     * 
     * @return string
     */
    abstract protected function getDetalheClass();
    
    /**
     * O método deverá retornar o nome da classe que será utilizada como Trailer do arquivo
     * 
     * @return string
     */
    abstract protected function getTrailerClass();

    /**
     * 
     * @param RecursiveValidator $validator
     */
    public function __construct(RecursiveValidator $validator)
    {
        $this->validator = $validator;
    }
    
    public function getHeader()
    {
        if (!$this->isLoaded()) {
            throw new ArquivoNotLoadedException();
        }
        return $this->header;
    }
    
    public function getDetalhes()
    {
        if (!$this->isLoaded()) {
            throw new ArquivoNotLoadedException();
        }
        return $this->detalhes;
    }
    
    public function getTrailer()
    {
        if (!$this->isLoaded()) {
            throw new ArquivoNotLoadedException();
        }
        return $this->trailer;
    }
    
    /**
     * 
     * @param UploadedFile $file
     */
    public function load(UploadedFile $file)
    {
        if ($this->isLoaded()) {
            throw new ArquivoLoadedException();
        }
        
        $rawData = file_get_contents($file->getPath() . '/' . $file->getFilename());
        $data = array_filter(explode(PHP_EOL, str_replace("\r", '', $rawData)));
        
        for ($i = 0; $i < count($data); $i++) {
            if (self::ROW_SIZE !== strlen($data[$i])) {
                throw new EstruturaArquivoInvalidaException();
            }
            
            if (0 === $i) {
                $headerClass = $this->getHeaderClass();
                $this->header = new $headerClass($data[$i]);
            } elseif (count($data) - 1 === $i) {
                $trailerClass = $this->getTrailerClass();
                $this->trailer = new $trailerClass($data[$i], $i + 1);
            } else {
                $detalheClass = $this->getDetalheClass();
                $this->detalhes[] = new $detalheClass($data[$i], $i + 1);
            }
        }
        
        $this->errors = $this->validator->validate($this);
        $this->isLoaded = true;
    }
    
    /**
     * 
     * @return boolean
     */
    public function isLoaded()
    {
        return $this->isLoaded;
    }
    
    /**
     * 
     * @return boolean
     */
    public function hasError()
    {
        return $this->errors && 0 !== $this->errors->count();
    }
    
    /**
     * 
     * @return array
     */
    public function getErrors()
    {
        if ($this->hasError()) {
            return array_unique(array_map(function (ConstraintViolationInterface $violation) {
                return $violation->getMessage();
            }, $this->errors->getIterator()->getArrayCopy()));
        }
    }    
    
}
