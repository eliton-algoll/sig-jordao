<?php

namespace AppBundle\Cpb\RetornoPagamento;

use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as AppAssert;
use AppBundle\Cpb\DicionarioCpb;

final class Detalhe
{
    /**
     *
     * @var integer
     */
    private $linha;
    
    /**
     *
     * @var string          
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
     *      value = 80,
     *      message = "O tipo de lote do arquivo selecionado {{ value }} é inválido para arquivo de retorno de pagamento que deverá ter o tipo 80. Selecione novo arquivo e refaça a operação."
     * )
     */
    private $csTipoLote;
    
    /**
     *
     * @var string     
     */
    private $nuNib;
    
    /**
     *
     * @var string     
     */
    private $dtFimPeriodo;
    
    /**
     *
     * @var string     
     */
    private $dtIniPeriodo;
    
    /**
     *
     * @var string     
     */
    private $csNaturCredito;
    
    /**
     *
     * @var string     
     */
    private $dtMovCredito;
    
    /**
     *
     * @var string     
     */
    private $idOrgaoPagador;
    
    /**
     *
     * @var string
     * 
     * @Assert\Regex(
     *      pattern = "/\d/"
     * )
     */
    private $vlCredito;
    
    /**
     *
     * @var string     
     */
    private $csUnidMonet;
    
    /**
     *
     * @var string     
     */
    private $dtFimValidade;
    
    /**
     *
     * @var string     
     */
    private $dtIniValidade;
    
    /**
     *
     * @var string     
     */
    private $inCrBloqueado;
    
    /**
     *
     * @var string
     * 
     * @Assert\Regex(
     *      pattern = "/[0-9A-Z]/"
     * )
     */
    private $nuConta;
    
    /**
     *
     * @var string     
     */
    private $csOrigemOrcamento;
    
    /**
     *
     * @var string     
     */
    private $inPioneira;
    
    /**
     *
     * @var string     
     */
    private $idNit;
    
    /**
     *
     * @var string     
     */
    private $csTipoCredito;
    
    /**
     *
     * @var string     
     */
    private $csEspecie;
    
    /**
     *
     * @var string     
     */
    private $nuSeqRms;
    
    /**
     *
     * @var string     
     */
    private $nuSeqCredito;
    
    /**
     *
     * @var string     
     */
    private $sitCmd;
    
    /**
     *
     * @var string     
     */
    private $sitRms;
    
    /**
     *
     * @var string     
     */
    private $nuCtrlCred;
    
    /**
     * 
     * @param string $string
     */
    public function __construct($string, $linha)
    {
        $this->linha = (integer) $linha;
        $this->csTipoRegistro = substr($string, 0, 1);
        $this->nuSeqRegistro = substr($string, 1, 7);
        $this->csTipoLote = substr($string, 8, 2);
        $this->nuNib = substr($string, 10, 10);
        $this->dtFimPeriodo = substr($string, 20, 8);
        $this->dtIniPeriodo = substr($string, 28, 8);
        $this->csNaturCredito = substr($string, 36, 2);
        $this->dtMovCredito = substr($string, 38, 8);
        $this->idOrgaoPagador = substr($string, 46, 6);
        $this->vlCredito = substr($string, 52, 12);
        $this->csUnidMonet = substr($string, 64, 1);
        $this->dtFimValidade = substr($string, 65, 8);
        $this->dtIniValidade = substr($string, 73, 8);
        $this->inCrBloqueado = substr($string, 81, 1);
        $this->nuConta = substr($string, 82, 10);
        $this->csOrigemOrcamento = substr($string, 92, 2);
        $this->inPioneira = substr($string, 94, 1);
        $this->idNit = substr($string, 100, 11);
        $this->csTipoCredito = substr($string, 111, 2);
        $this->csEspecie = substr($string, 113, 3);
        $this->nuSeqRms = substr($string, 116, 6);
        $this->nuSeqCredito = substr($string, 122, 11);
        $this->sitCmd = substr($string, 133, 4);
        $this->sitRms = substr($string, 137, 1);
        $this->nuCtrlCred = substr($string, 233, 7);        
    }
    
    /**
     * 
     * @return integer
     */
    public function getLinha()
    {
        return $this->linha;
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
    public function getNuNib()
    {
        return $this->nuNib;
    }
    
    /**
     * A identificação do profissional no arquivo é pelo NU-NIB onde os 9 primeiros números representa os 9 primeiros números do CPF
     * 
     * @return string
     */
    public function getPartialCpf()
    {
        return substr($this->nuNib, 0, 9);
    }

    /**
     * 
     * @return string
     */
    public function getDtFimPeriodo()
    {
        return $this->dtFimPeriodo;
    }

    /**
     * 
     * @return string
     */
    public function getDtIniPeriodo()
    {
        return $this->dtIniPeriodo;
    }

    /**
     * 
     * @return string
     */
    public function getCsNaturCredito()
    {
        return $this->csNaturCredito;
    }

    /**
     * 
     * @return string
     */
    public function getDtMovCredito()
    {
        return $this->dtMovCredito;
    }

    /**
     * 
     * @return string
     */
    public function getIdOrgaoPagador()
    {
        return $this->idOrgaoPagador;
    }

    /**
     * 
     * @return string
     */
    public function getVlCredito()
    {
        return $this->vlCredito;
    }

    /**
     * 
     * @return string
     */
    public function getCsUnidMonet()
    {
        return $this->csUnidMonet;
    }

    /**
     * 
     * @return string
     */
    public function getDtFimValidade()
    {
        return $this->dtFimValidade;
    }

    /**
     * 
     * @return string
     */
    public function getDtIniValidade()
    {
        return $this->dtIniValidade;
    }

    /**
     * 
     * @return string
     */
    public function getInCrBloqueado()
    {
        return $this->inCrBloqueado;
    }

    /**
     * 
     * @return string
     */
    public function getNuConta()
    {
        return $this->nuConta;
    }

    /**
     * 
     * @return string
     */
    public function getCsOrigemOrcamento()
    {
        return $this->csOrigemOrcamento;
    }

    /**
     * 
     * @return string
     */
    public function getInPioneira()
    {
        return $this->inPioneira;
    }

    /**
     * 
     * @return string
     */
    public function getIdNit()
    {
        return $this->idNit;
    }

    /**
     * 
     * @return string
     */
    public function getCsTipoCredito()
    {
        return $this->csTipoCredito;
    }

    /**
     * 
     * @return string
     */
    public function getCsEspecie()
    {
        return $this->csEspecie;
    }

    /**
     * 
     * @return string
     */
    public function getNuSeqRms()
    {
        return $this->nuSeqRms;
    }

    /**
     * 
     * @return string
     */
    public function getNuSeqCredito()
    {
        return $this->nuSeqCredito;
    }

    /**
     * 
     * @return string
     */
    public function getSitCmd()
    {
        return $this->sitCmd;
    }

    /**
     * 
     * @return string
     */
    public function getSitRms()
    {
        return $this->sitRms;
    }

    /**
     * 
     * @return string
     */
    public function getNuCtrlCred()
    {
        return $this->nuCtrlCred;
    }
    
    /**
     * 
     * @return boolean
     */
    public function isPagamentoEfetuado()
    {
        return $this->sitCmd === DicionarioCpb::getValues('SIT-CMD')['Registro OK'];
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
        
        $fields = [
            ['value' => $this->dtFimPeriodo, 'name' => 'DT-FIM-PERIODO'],
            ['value' => $this->dtIniPeriodo, 'name' => 'DT-INI-PERIODO'],
            ['value' => $this->dtFimValidade, 'name' => 'DT-FIM-VALIDATE'],
            ['value' => $this->dtIniValidade, 'name' => 'DT-INI-VALIDADE'],
            ['value' => $this->dtMovCredito, 'name' => 'DT-MOV-CREDITO'],
        ];
        
        foreach ($fields as $field) {
            $violations->addAll(
                $validator->validate(
                    $field['value'],
                    new AppAssert\DateTime([
                        'format' => 'Ymd',                        
                        'message' => 'Valor inválido para o campo '. $field['name'] .': Arquivo de retorno de pagamento. Linha '. $this->linha .'. Selecione novo arquivo e refaça a operação.'
                    ])
                )
            );
        }
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
                $this->csTipoRegistro,
                new Assert\EqualTo(['value' => '2', 'message' => 'O arquivo selecionado possui tipo de registro inválido: {{ value }}. Linha ' . $this->linha])
            )
        );
        
        $fields = [
            ['value' => $this->csUnidMonet, 'name' => 'CS-UNIT-MONET'],
            ['value' => $this->csOrigemOrcamento, 'name' => 'CS-ORIGEM-ORCAMENTO'],
            ['value' => $this->csTipoCredito, 'name' => 'CS-TIPO-CREDITO'],
        ];
        
        foreach ($fields as $field) {
            $violations->addAll(
                $validator->validate(
                    $field['value'],
                    new AppAssert\CpbDefaults(['campo' => $field['name'], 'linha' => $this->linha])
                )
            );
        }
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
            [ 'value' => $this->nuSeqRegistro, 'name' => 'NU-SEQ-REGISTRO' ],
            [ 'value' => $this->nuNib, 'name' => 'NU-NIB' ],
            [ 'value' => $this->csNaturCredito, 'name' => 'CS-NATUR-CREDITO' ],
            [ 'value' => $this->idOrgaoPagador, 'name' => 'ID-ORGAO-PAGADOR' ],
            [ 'value' => $this->vlCredito, 'name' => 'VL-CREDITO' ],
            [ 'value' => $this->nuConta, 'name' => 'NU-CONTA' ],
            [ 'value' => $this->csOrigemOrcamento, 'name' => 'CS-ORIGEM-ORCAMENTO' ],
            [ 'value' => $this->inPioneira, 'name' => 'IN-PIONEIRA' ],
            [ 'value' => $this->idNit, 'name' => 'ID-NIT' ],
            [ 'value' => $this->csTipoCredito, 'name' => 'CS-TIPO-CREDITO' ],
            [ 'value' => $this->csEspecie, 'name' => 'CS-ESPECIE' ],
            [ 'value' => $this->nuSeqRms, 'name' => 'NU-SEQ-RMS' ],
            [ 'value' => $this->nuSeqCredito, 'name' => 'NU-SEQ-CREDITO' ],
            [ 'value' => $this->sitCmd, 'name' => 'SIT-CMD' ],
            [ 'value' => $this->sitRms, 'name' => 'SIT-RMS' ],
        ];
        
        foreach ($fields as $field) {
            $violations->addAll(
                $validator->validate(
                    $field['value'],
                    new Assert\Regex([
                        'pattern' => '/[^0-9 ]/',
                        'match' => false,
                        'message' => 'Valor não numérico identificado para o campo '. $field['name'] .': {{ value }}. Linha '. $this->linha .'. Selecione novo arquivo e refaça a operação.'
                    ])
                )
            );
        }
    }
    
    /**
     * 
     * @param ExecutionContextInterface $context
     * 
     * @Assert\Callback()
     */
    public function validateALphaNum(ExecutionContextInterface $context)
    {
        $validator = $context->getValidator();
        $violations = $context->getViolations();
        
        $violations->addAll(
            $validator->validate(
                $this->nuConta,
                new Assert\Regex([
                    'pattern' => '/[0-9A-Z ]/',
                    'message' => 'Valor inválido para o campo NU-CONTA: {{ value }}. Linha '. $this->linha .'. Selecione novo arquivo e refaça a operação.',
                ])
            )
        );
    }
}
