<?php

namespace AppBundle\Cpb\RetornoPagamento;

use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as AppAssert;

final class Trailer
{
    /**
     *
     * @var string    
     * 
     * @Assert\EqualTo(
     *      value = 3,
     *      message = "O arquivo selecionado tem seu último registro com classificação diferente de “3 – TRAILER (rodapé)”, sendo essa informação obrigatória para o arquivo de retorno. Selecione novo arquivo e refaça a operação."
     * )      
     */
    private $csTipoRegistro;
    
    /**
     *
     * @var string
     * 
     * @Assert\Regex(
     *      pattern = "/\d/"
     * )
     */
    private $nuSeqRegistro;
    
    /**
     *
     * @var string
     * 
     * @Assert\EqualTo(
     *      value = 80,
     *      message = "O tipo de lote do arquivo selecionado {{ value }} é inválido para arquivo de retorno de pagamento que deverá ter o tipo 80. Selecione novo arquivo e refaça a operação."
     * )
     */
    private $csTipoLote;
    
    /**
     *
     * @var string
     * 
     * @Assert\Regex(
     *      pattern = "/\d/"
     * )
     */
    private $idBanco;
    
    /**
     *
     * @var string
     * 
     * @Assert\Regex(
     *      pattern = "/\d/"
     * )
     */
    private $qtRegDetalhe;
    
    /**
     *
     * @var string
     * 
     * @Assert\Regex(
     *      pattern = "/\d/"
     * )
     */
    private $vlRegDetalhe;
    
    /**
     *
     * @var string
     * 
     * @Assert\Regex(
     *      pattern = "/\d/"
     * )
     */
    private $nuSeqLote;
    
    /**
     * 
     * @param string $trailerString
     */
    public function __construct($trailerString)
    {
        $this->csTipoRegistro = substr($trailerString, 0, 1);
        $this->nuSeqRegistro = substr($trailerString, 1, 7);
        $this->csTipoLote = substr($trailerString, 8, 2);
        $this->idBanco = substr($trailerString, 10, 3);
        $this->qtRegDetalhe = substr($trailerString, 13, 8);
        $this->vlRegDetalhe = substr($trailerString, 21, 17);
        $this->nuSeqLote = substr($trailerString, 38, 2);
    }
    
    /**
     * 
     * @return string
     */
    public function getCsTipoRegistro()
    {
        return $this->csTipoRegistro;
    }

    /**
     * 
     * @return string
     */
    public function getNuSeqRegistro()
    {
        return $this->nuSeqRegistro;
    }

    /**
     * 
     * @return string
     */
    public function getCsTipoLote()
    {
        return $this->csTipoLote;
    }

    /**
     * 
     * @return string
     */
    public function getIdBanco()
    {
        return $this->idBanco;
    }

    /**
     * 
     * @return string
     */
    public function getQtRegDetalhe()
    {
        return $this->qtRegDetalhe;
    }

    /**
     * 
     * @return string
     */
    public function getVlRegDetalhe()
    {
        return $this->vlRegDetalhe;
    }

    /**
     * 
     * @return string
     */
    public function getNuSeqLote()
    {
        return $this->nuSeqLote;
    }
    
    /**
     * 
     * @param ExecutionContextInterface $context
     * 
     * @Assert\Callback()
     */
    public function validateDefaults(ExecutionContextInterface $context)
    {
        $validator = $context->getValidator();
        $violations = $context->getViolations();
        
        $violations->addAll(
            $validator->validate(
                $this->idBanco,
                new AppAssert\CpbDefaults(['campo' => 'ID-BANCO', 'linha' => $this->getNuSeqRegistro()])
            )
        );
    }
}
