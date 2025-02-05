<?php

namespace AppBundle\Cpb\RetornoPagamento;

use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as AppAssert;

final class Header
{
    /**
     *
     * @var string 
     * 
     * @Assert\EqualTo(
     *      value = 1,
     *      message = "O arquivo selecionado tem seu primeiro registro com classificação diferente de “1 – Header (cabeçalho)”, sendo essa informação obrigatória para o arquivo de retorno. Selecione novo arquivo e refaça a operação."
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
     *      message = "O tipo de lote do arquivo selecionado {{ value }} é inválido para arquivo de retorno de pagamento que deverá ter o tipo {{ compared_value }}. Selecione novo arquivo e refaça a operação."
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
    private $csMeioPagto;
    
    /**
     *
     * @var string
     * 
     * @Assert\Regex(
     *      pattern = "/\d/"
     * )
     * @AppAssert\DateTime(
     *      format = "Ymd"
     * )
     */
    private $dtGravacaoLote;
    
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
     * @var string
     * 
     * @Assert\Regex(
     *      pattern = "/\d/"
     * )
     */
    private $nuCodConv;
    
    /**
     *
     * @var string
     * 
     * @Assert\Regex(
     *      pattern = "/\d/"
     * )
     */
    private $nuCtrlTrans;
    
    /**
     * 
     * @param string $headerString
     */
    public function __construct($headerString)
    {
        $this->csTipoRegistro = substr($headerString, 0, 1);
        $this->nuSeqRegistro = substr($headerString, 1, 7);
        $this->csTipoLote = substr($headerString, 8, 2);
        $this->idBanco = substr($headerString, 10, 3);
        $this->csMeioPagto = substr($headerString, 13, 2);
        $this->dtGravacaoLote = substr($headerString, 15, 8);
        $this->nuSeqLote = substr($headerString, 23, 2);
        $this->nuCodConv = substr($headerString, 32, 6);
        $this->nuCtrlTrans = substr($headerString, 94, 6);
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
    public function getCsMeioPagto()
    {
        return $this->csMeioPagto;
    }

    /**
     * 
     * @return string
     */
    public function getDtGravacaoLote()
    {
        return $this->dtGravacaoLote;
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
     * @return string
     */
    public function getNuCodConv()
    {
        return $this->nuCodConv;
    }

    /**
     * 
     * @return string
     */
    public function getNuCtrlTrans()
    {
        return $this->nuCtrlTrans;
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
                $this->csMeioPagto,
                new AppAssert\CpbDefaults(['campo' => 'CS-MEIO-PAGTO', 'linha' => '1'])
            )
        );
        
        $violations->addAll(
            $validator->validate(
                $this->idBanco,
                new AppAssert\CpbDefaults(['campo' => 'ID-BANCO', 'linha' => '1'])
            )
        );
    }
}
