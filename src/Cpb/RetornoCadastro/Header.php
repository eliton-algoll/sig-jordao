<?php

namespace App\Cpb\RetornoCadastro;

use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as AppAssert;

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
     */
    private $nuSeqRegistro;
    
    /**
     *
     * @var string
     * 
     * @Assert\EqualTo(
     *      value = 81,
     *      message = "O tipo de lote do arquivo selecionado {{ value }} é inválido para arquivo de retorno de pagamento que deverá ter o tipo {{ compared_value }}. Selecione novo arquivo e refaça a operação."
     * )
     */
    private $csTipoLote;
    
    /**
     *
     * @var string          
     */
    private $idBanco;
    
    /**
     *
     * @var string 
     */
    private $csMeioPagto;
    
    /**
     *
     * @var string
     *      
     * @AppAssert\DateTime(
     *      format = "Ymd"
     * )
     */
    private $dtGravacaoLote;
    
    /**
     *
     * @var string          
     */
    private $nuSeqLote;
    
    /**
     *
     * @var string     
     */
    private $nuCodConv;
    
    /**
     *
     * @var string     
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
                $this->idBanco,
                new AppAssert\CpbDefaults(['campo' => 'ID-BANCO', 'linha' => $this->getNuSeqRegistro()])
            )
        );
        
        $violations->addAll(
            $validator->validate(
                $this->csMeioPagto,
                new AppAssert\CpbDefaults(['campo' => 'CS-MEIO-PAGTO', 'linha' => $this->getNuSeqRegistro()])
            )
        );
    }
    
    /**
     * 
     * @param ExecutionContextInterface $context
     * 
     * @Assert\Callback()
     */
    public function validateDates(ExecutionContextInterface $context)
    {
        $validator = $context->getValidator();
        $violations = $context->getViolations();
        
        $violations->addAll(
            $validator->validate(
                $this->dtGravacaoLote,
                new AppAssert\DateTime([
                    'format' => 'Ymd',
                    'message' => 'Valor inválido para o campo DT-GRACACAO-LOTE: '. $this->getDtGravacaoLote() .'. Linha 1. Selecione novo arquivo e refaça a operação.'])
            )
        );
    }
    
    /**
     * 
     * @param ExecutionContextInterface $context
     * 
     * @Assert\Callback()
     */
    public function validateNumbers(ExecutionContextInterface $context)
    {
        $validator = $context->getValidator();
        $violations = $context->getViolations();
        
        $fields = [
            ['value' => $this->nuSeqRegistro, 'name' => 'NU-SEQ-REGISTRO'],
            ['value' => $this->nuSeqLote, 'name' => 'NU-SEQ-LOTE'],
            ['value' => $this->nuCodConv, 'name' => 'NU-COD-CONV'],
            ['value' => $this->nuCtrlTrans, 'name' => 'NU-CTRL-TRANS'],
        ];
        
        foreach ($fields as $field) {
            $violations->addAll(
                $validator->validate(
                    $field['value'],
                    new Assert\Regex([
                        'pattern' => '/[^0-9 ]/',
                        'match' => false,
                        'message' => 'Valor não numérico identificado para o campo '. $field['name'] .': {{ value }}. Linha 1. Selecione novo arquivo e refaça a operação.'])
                )
            );
        }
    }
}
