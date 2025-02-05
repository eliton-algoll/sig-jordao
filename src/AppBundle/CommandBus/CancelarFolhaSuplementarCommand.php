<?php

namespace AppBundle\CommandBus;

use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Entity\FolhaPagamento;

final class CancelarFolhaSuplementarCommand
{
    /**
     *
     * @var FolhaPagamento
     */
    private $folhaPagamento;
    
    /**
     *
     * @var string
     * 
     * @Assert\NotBlank()
     * @Assert\Length(
     *      max = 4000,
     *      maxMessage = "A justificativa deve ter no máximo {{ limit }}."
     * )
     */
    private $dsJustificativa;
    
    /**
     * 
     * @param FolhaPagamento $folhaPagamento
     */
    public function __construct(FolhaPagamento $folhaPagamento, $dsJustificativa)
    {
        $this->folhaPagamento = $folhaPagamento;
        $this->dsJustificativa = $dsJustificativa;
    }
    
    /**
     * 
     * @return FolhaPagamento
     */
    public function getFolhaPagamento()
    {
        return $this->folhaPagamento;
    }
    
    /**
     * 
     * @return string
     */
    public function getDsJustificativa()
    {
        return $this->dsJustificativa;
    }
}
