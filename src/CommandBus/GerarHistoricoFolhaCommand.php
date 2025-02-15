<?php

namespace App\CommandBus;

use App\Entity\FolhaPagamento;
use App\Entity\ProjetoPessoa;

class GerarHistoricoFolhaCommand {

    private $participantes;

    /**
     * @param array<\App\Entity\ProjetoFolhaPagamento> $participantes
     */
    public function __construct(array $projetoFolhaPagamento)
    {
        foreach ($projetoFolhaPagamento as $pfp) {
            if ($pfp instanceof \App\Entity\ProjetoFolhaPagamento) {
                foreach($pfp->getProjeto()->getProjetosPessoas() as $projetoPessoa) {
                    $this->addParticipantes(
                        $projetoPessoa,
                        $pfp->getFolhaPagamento()
                    );
                }
            }
        }
    }

    /**
     * @param ProjetoPessoa $projetoPessoa
     * @param FolhaPagamento $folhaPagamento
     */
    public function addParticipantes(ProjetoPessoa $projetoPessoa, FolhaPagamento $folhaPagamento)
    {
        $this->participantes[] = array(
            'projetoPessoa' => $projetoPessoa,
            'folhaPagamento' => $folhaPagamento
        );
    }

    /**
     * @return array
     */
    public function getParticipantes()
    {
        return $this->participantes;
    }
}