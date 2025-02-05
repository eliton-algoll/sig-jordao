<?php

namespace AppBundle\Cpb\RetornoCadastro;

use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as AppAssert;

final class Trailer
{
    /**
     *
     * @var integer
     */
    private $linha;
    
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
     */
    private $nuSeqRegistro;
    
    /**
     *
     * @var string
     * 
     * @Assert\EqualTo(
     *      value = 81,
     *      message = "O tipo de lote do arquivo selecionado {{ compared_value }} é inválido para arquivo de retorno de pagamento que deverá ter o tipo 80. Selecione novo arquivo e refaça a operação."
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
    private $qtRegDetalhe;
    
    /**
     *
     * @var string     
     */
    private $nuSeqLote;
    
    /**
     * 
     * @param string $string
     * @param integer $linha
     */
    public function __construct($string, $linha)
    {
        $this->linha = (integer) $linha;
        $this->csTipoRegistro = substr($string, 0, 1);
        $this->nuSeqRegistro = substr($string, 1, 7);
        $this->csTipoLote = substr($string, 8, 2);
        $this->idBanco = substr($string, 10, 3);
        $this->qtRegDetalhe = substr($string, 13, 8);        
        $this->nuSeqLote = substr($string, 21, 2);
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
                new AppAssert\CpbDefaults(['campo' => 'ID-BANCO', 'linha' => $this->linha])
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
            ['value' => $this->qtRegDetalhe, 'name' => 'QT-REG-DETALHE'],
            ['value' => $this->nuSeqLote, 'name' => 'NU-SEQ-LOTE'],
        ];
        
        foreach ($fields as $field) {
            $violations->addAll(
                $validator->validate(
                    $field['value'],
                    new Assert\Regex([
                        'pattern' => '/[^0-9 ]/',
                        'match' => false,
                        'message' => 'Valor não numérico identificado para o campo '. $field['name'] .': {{ value }}. Linha 1. Selecione novo arquivo e refaça a operação.'
                    ])
                )
            );
        }
    }
}
