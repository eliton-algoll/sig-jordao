<?php

namespace App\CommandBus;

use App\Entity\PlanejamentoAnoFolha;

final class InativarPlanejamentoAberturaFolhaPagamentoCommand
{
    /**
     *
     * @var PlanejamentoAnoFolha
     */
    private $planejamentoAnoFolha;
    
    /**
     * 
     * @param PlanejamentoAnoFolha $planejamentoAnoFolha
     */
    public function __construct(PlanejamentoAnoFolha $planejamentoAnoFolha)
    {
        $this->planejamentoAnoFolha = $planejamentoAnoFolha;
    }

    /**
     * 
     * @return PlanejamentoAnoFolha
     */
    public function getPlanejamentoAnoFolha()
    {
        return $this->planejamentoAnoFolha;
    }
}
