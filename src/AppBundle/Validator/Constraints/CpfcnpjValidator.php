<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;

class CpfcnpjValidator extends ConstraintValidator
{
    /**
     * @param string $value
     * @param Constraint $constraint
     * @throws ConstraintDefinitionException
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint->aceitar) {
            throw new ConstraintDefinitionException('É necessário definer a opção "aceitar" da restrição.');
        }

        if (!in_array($constraint->aceitar, array('cpf','cnpj','cpfcnpj'))) {
            throw new ConstraintDefinitionException('A opção "aceitar" pode conter apenas os valores "cpf", "cnpj" ou "cpfcnpj".');
        }
        
        if (null === $value) {
            return;
        }

        switch ($constraint->aceitar) {

            case 'cnpj':
                if (!$this->checkCNPJ($value, $constraint->aceitar_formatado)) {
                    $this->context->buildViolation($constraint->message_cnpj)
                        ->setParameter('%value%', $value)
                        ->addViolation();
                }
                break;

            case 'cpf':
                if (!$this->checkCPF($value, $constraint->aceitar_formatado)) {
                    $this->context->buildViolation($constraint->message_cpf)
                        ->setParameter('%value%', $value)
                        ->addViolation();
                }
                break;

            case 'cpfcnpj':
            default:
                if (!($this->checkCPF($value, $constraint->aceitar_formatado) || $this->checkCNPJ($value, $constraint->aceitar_formatado))) {
                    $this->context->buildViolation($constraint->message_cpfcnpj)
                        ->setParameter('%value%', $value)
                        ->addViolation();
                }
                break;
        }
    }


    /**
     * Baseado em http://www.vivaolinux.com.br/script/Validacao-de-CPF-e-CNPJ/
     * Algoritmo em http://www.geradorcpf.com/algoritmo_do_cpf.htm
     * @param $cpf string
     * @return boolean
     */
    protected function checkCPF($cpf, $aceitar_formatado) {

        // Limpando caracteres especiais
        if($aceitar_formatado){
            $cpf = $this->valueClean($cpf);
        }

        // Quantidade mínima de caracteres ou erro
        if (strlen($cpf) <> 11) return false;

        // Primeiro dígito
        $soma = 0;
        for ($i = 0; $i < 9; $i++) {
            $soma += ((10-$i) * $cpf[$i]);
        }
        $d1 = 11 - ($soma % 11);
        if ($d1 >= 10) $d1 = 0;

        // Segundo Dígito
        $soma = 0;
        for ($i = 0; $i < 10; $i++) {
            $soma += ((11-$i) * $cpf[$i]);
        }
        $d2 = 11 - ($soma % 11);
        if ($d2 >= 10) $d2 = 0;

        if ($d1 == $cpf[9] && $d2 == $cpf[10]) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Baseado em http://www.vivaolinux.com.br/script/Validacao-de-CPF-e-CNPJ/
     * Algoritmo em http://www.geradorcnpj.com/algoritmo_do_cnpj.htm
     * @param $cnpj string
     * @return boolean
     */
    protected function checkCNPJ($cnpj, $aceitar_formatado) {
        if($aceitar_formatado){
            $cnpj = $this->valueClean($cnpj);
        }
        if (strlen($cnpj) <> 14) return false;

        // Primeiro dígito
        $multiplicadores = array(5,4,3,2,9,8,7,6,5,4,3,2);
        $soma = 0;
        for ($i = 0; $i <= 11; $i++) {
            $soma += $multiplicadores[$i] * $cnpj[$i];
        }
        $d1 = 11 - ($soma % 11);
        if ($d1 >= 10) $d1 = 0;

        // Segundo dígito
        $multiplicadores = array(6,5,4,3,2,9,8,7,6,5,4,3,2);
        $soma = 0;
        for ($i = 0; $i <= 12; $i++) {
            $soma += $multiplicadores[$i] * $cnpj[$i];
        }
        $d2 = 11 - ($soma % 11);
        if ($d2 >= 10) $d2 = 0;

        if ($cnpj[12] == $d1 && $cnpj[13] == $d2) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Retira caracteres especiais
     * @param $value string
     * @return string
     */
    protected function valueClean($value)
    {
        $value = str_replace (array(')','(','/','.','-',' '),'',$value);
        if(strlen($value) == 15)
        {
            $value =  substr($value, 1, 15);
        }
        return $value;
    }
}