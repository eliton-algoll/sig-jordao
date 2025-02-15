<?php

namespace App\Cpb\RetornoCadastro;

use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as AppAssert;
use App\Cpb\DicionarioCpb;

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
    private $idOrgaoPagador;
    
    /**
     *
     * @var string 
     */
    private $csEspecie;
    
    /**
     *
     * @var string 
     */
    private $idAgenciaConv;
    
    /**
     *
     * @var string 
     */
    private $inPrestacaoUnica;
    
    /**
     *
     * @var string 
     */
    private $nuConta;
    
    /**
     *
     * @var string 
     */
    private $nuCpf;
    
    /**
     *
     * @var string 
     */
    private $nmBeneficiario;
    
    /**
     *
     * @var string 
     */
    private $idNit;
    
    /**
     *
     * @var string 
     */
    private $teEndereco;
    
    /**
     *
     * @var string 
     */
    private $teBairro;
    
    /**
     *
     * @var string 
     */
    private $nuCep;
    
    /**
     *
     * @var string 
     */
    private $dtNasc;
    
    /**
     *
     * @var string 
     */
    private $nmMae;
    
    /**
     *
     * @var string 
     */
    private $nuDiaUtil;
    
    /**
     *
     * @var string 
     */
    private $csTipoDadoCad;
    
    /**
     *
     * @var string 
     */
    private $dtUltAtuEnd;
    
    /**
     *
     * @var string 
     */
    private $nuSeqRms;
    
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
     * @param string $string
     * @param integer $linha
     */
    public function __construct($string, $linha)
    {
        $this->linha = (integer) $linha;
        $this->csTipoRegistro = substr($string, 0, 1);
        $this->nuSeqRegistro = substr($string, 1, 7);
        $this->csTipoLote = substr($string, 8, 2);
        $this->nuNib = substr($string, 10, 10);
        $this->idOrgaoPagador = substr($string, 20, 6);
        $this->csEspecie = substr($string, 26, 3);
        $this->idAgenciaConv = substr($string, 29, 8);
        $this->inPrestacaoUnica = substr($string, 37, 1);
        $this->nuConta = substr($string, 38, 10);
        $this->nuCpf = substr($string, 48, 11);
        $this->nmBeneficiario = substr($string, 72, 28);
        $this->idNit = substr($string, 100, 11);
        $this->teEndereco = substr($string, 111, 40);
        $this->teBairro = substr($string, 151, 17);
        $this->nuCep = substr($string, 168, 8);
        $this->dtNasc = substr($string, 176, 8);
        $this->nmMae = substr($string, 184, 32);
        $this->nuDiaUtil = substr($string, 216, 2);
        $this->csTipoDadoCad = substr($string, 218, 2);
        $this->dtUltAtuEnd = substr($string, 220, 8);
        $this->nuSeqRms = substr($string, 228, 6);
        $this->sitCmd = substr($string, 234, 4);
        $this->sitRms = substr($string, 238, 1);
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
    public function getCsEspecie()
    {
        return $this->csEspecie;
    }

    /**
     * 
     * @return string
     */
    public function getIdAgenciaConv()
    {
        return $this->idAgenciaConv;
    }

    /**
     * 
     * @return string
     */
    public function getInPrestacaoUnica()
    {
        return $this->inPrestacaoUnica;
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
    public function getNuCpf()
    {
        return $this->nuCpf;
    }

    /**
     * 
     * @return string
     */
    public function getNmBeneficiario()
    {
        return $this->nmBeneficiario;
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
    public function getTeEndereco()
    {
        return $this->teEndereco;
    }

    /**
     * 
     * @return string
     */
    public function getTeBairro()
    {
        return $this->teBairro;
    }

    /**
     * 
     * @return string
     */
    public function getNuCep()
    {
        return $this->nuCep;
    }

    /**
     * 
     * @return string
     */
    public function getDtNasc()
    {
        return $this->dtNasc;
    }

    /**
     * 
     * @return string
     */
    public function getNmMae()
    {
        return $this->nmMae;
    }

    /**
     * 
     * @return string
     */
    public function getNuDiaUtil()
    {
        return $this->nuDiaUtil;
    }

    /**
     * 
     * @return string
     */
    public function getCsTipoDadoCad()
    {
        return $this->csTipoDadoCad;
    }

    /**
     * 
     * @return string
     */
    public function getDtUltAtuEnd()
    {
        return $this->dtUltAtuEnd;
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
     * @return boolean
     */
    public function isContaCriada()
    {
        return $this->sitCmd == DicionarioCpb::getValues('SIT-CMD')['Registro OK'];
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
                $this->dtNasc,
                new AppAssert\DateTime(['format' => 'Ymd', 'message' => 'Valor inválido para o campo DT-NASC: {{ value }}. Linha '. $this->linha .'. Selecione novo arquivo e refaça a operação.'])
            )
        );
        
        $violations->addAll(
            $validator->validate(
                $this->dtUltAtuEnd,
                new AppAssert\DateTime(['format' => 'Ymd', 'message' => 'Valor inválido para o campo DT-ULT-ATU-END: {{ value }}. Linha '. $this->linha .'. Selecione novo arquivo e refaça a operação.'])
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
            ['value' => $this->nuNib, 'name' => 'NU-NIB'],
            ['value' => $this->idOrgaoPagador, 'name' => 'ID-ORGAO-PAGADOR'],
            ['value' => $this->csEspecie, 'name' => 'CS-ESPECIE'],
            ['value' => $this->idAgenciaConv, 'name' => 'ID-AGENCIA-CONV'],            
            ['value' => $this->inPrestacaoUnica, 'name' => 'IN-PRESTACAO-UNICA'],
            ['value' => $this->nuCpf, 'name' => 'NU-CPF'],
            ['value' => $this->idNit, 'name' => 'ID-NIT'],
            ['value' => $this->nuDiaUtil, 'name' => 'NU-DIA-UTIL'],
            ['value' => $this->csTipoDadoCad, 'name' => 'CS-TIPO-DADO-CAD'],
            ['value' => $this->nuSeqRms, 'name' => 'NU-SEQ-RMS'],
            ['value' => $this->sitCmd, 'name' => 'SIT-CMD'],
            ['value' => $this->sitRms, 'name' => 'SIT-RMS'],
        ];
        
        foreach ($fields as $field) {
            $violations->addAll(
                $validator->validate(
                    $field['value'],
                    new Assert\Regex([
                        'pattern' => '/[^0-9 ]/',
                        'match' => false,
                        'message' => 'Valor não numérico identificado para o campo '. $field['name'] .': {{ value }}. Linha '. $this->linha .'. Selecione novo arquivo e refaça a operação.'])
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
        
        $fields = [
            ['value' => $this->nuConta, 'name' => 'NU-CONTA'],
            ['value' => $this->nmBeneficiario, 'name' => 'NM-BENEFICIARIO'],
            ['value' => $this->teEndereco, 'name' => 'TE-ENDERECO'],
            ['value' => $this->teBairro, 'name' => 'TE-BAIRRO'],
            ['value' => $this->nuCep, 'name' => 'NU-CEP'],
            ['value' => $this->nmMae, 'name' => 'NM-MAE'],
        ];
        
        foreach ($fields as $field) {
            $violations->addAll(
                $validator->validate(
                    $field['value'],
                    new Assert\Regex([
                        'pattern' => '/[0-9A-Z ]/',
                        'message' => 'Valor inválido para o campo '. $field['name'] .': {{ value }}. Linha '. $this->linha .'. Selecione novo arquivo e refaça a operação.',
                    ])
                )
            );
        }
    }
}
