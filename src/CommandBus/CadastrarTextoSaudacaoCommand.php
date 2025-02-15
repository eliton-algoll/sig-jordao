<?php

namespace App\CommandBus;

use App\Entity\TextoSaudacao;
use Symfony\Component\Validator\Constraints as Assert;


class CadastrarTextoSaudacaoCommand
{

    /**
     * @var TextoSaudacao
     */
    private $textoSaudacao;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min = 0, max = 4000)
     */
    private $dsTextoSaudacao;

    /**
     * @return string
     */
    public function getDsTextoSaudacao()
    {
        return $this->dsTextoSaudacao;
    }

    /**
     * @param string $dsTextoSaudacao
     * @return CadastrarTextoSaudacaoCommand
     */
    public function setDsTextoSaudacao($dsTextoSaudacao)
    {
        $this->dsTextoSaudacao = $dsTextoSaudacao;
        return $this;
    }

    /**
     * @return TextoSaudacao
     */
    public function getTextoSaudacao()
    {
        return $this->textoSaudacao;
    }



    /**
    * @param TextoSaudacao $textoSaudacao
    */
    public function setValuesByEntity(TextoSaudacao $textoSaudacao)
    {
        $this->dsTextoSaudacao = $textoSaudacao->getDsTextoSaudacao();
        $this->textoSaudacao = $textoSaudacao;
    }

}
