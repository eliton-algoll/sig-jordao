<?php

namespace AppBundle\CommandBus;

use Symfony\Component\Validator\Constraints as Assert;

class CadastrarEstabelecimentoCommand
{
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min = 1, max = 7)
     */
    private $coCnes;
    
    /**
     * @var integer
     * @Assert\NotBlank()
     * @Assert\Type(
     *     type="integer"
     * )
     */
    private $coProjeto;
    
    /**
     * @return string
     */
    public function getCoCnes()
    {
        return $this->coCnes;
    }

    /**
     * @param string $coCnes
     * @return CadastrarEstabelecimentoCommand
     */
    public function setCoCnes($coCnes)
    {
        $this->coCnes = $coCnes;
        return $this;
    }
    
    /**
     * @return integer
     */
    public function getCoProjeto()
    {
        return $this->coProjeto;
    }

    /**
     * @param integer $coProjeto
     * @return CadastrarEstabelecimentoCommand
     */
    public function setCoProjeto($coProjeto)
    {
        $this->coProjeto = $coProjeto;
        return $this;
    }
}