<?php

namespace AppBundle\CommandBus;

use Symfony\Component\Validator\Constraints as Assert;

class InativarEstabelecimentoCommand
{
    /**
     * @var integer
     * @Assert\NotBlank()
     */
    private $coSeqProjetoEstabelec;
    
    /**
     * @return integer
     */
    public function getCoSeqProjetoEstabelec()
    {
        return $this->coSeqProjetoEstabelec;
    }

    /**
     * @param integer $coSeqProjetoEstabelec
     * @return InativarEstabelecimentoCommand
     */
    public function setCoSeqProjetoEstabelec($coSeqProjetoEstabelec)
    {
        $this->coSeqProjetoEstabelec = $coSeqProjetoEstabelec;
        return $this;
    }
}