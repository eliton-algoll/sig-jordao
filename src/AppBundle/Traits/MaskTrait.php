<?php

namespace AppBundle\Traits;

trait MaskTrait
{
    /**
     * Coloca ou retira máscara no CNPJ dependendo do segundo parâmetro
     * 
     * @param string $string
     * @param boolean $add
     * @return string
     */
    public function maskcnpj($string, $add = true)
    {
        if($add) {
            $cnpj = preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $string);
        } else {
            $cnpj = $this->unmask($string);
        }
        
        return $cnpj;
    }
    
    /**
     * Coloca ou retira máscara no CPF dependendo do segundo parâmetro
     * 
     * @param string $string
     * @param boolean $add
     * @return string
     */
    public function maskcpf($string, $add = true) 
    {
        if($add) {
            $cpf = preg_replace("((\d{3})(\d{3})(\d{3})(\d{2}))", "$1.$2.$3-$4", $string);
        } else {
            $cpf = $this->unmask($string);
        }
        return $cpf;
    }
    
    /**
     * Retira máscara da string retornando apenas números
     * 
     * @param string $string
     * @return string
     */
    public function unmask($string)
    {
        return preg_replace('/[^\d]/', '', $string);
    }
}
