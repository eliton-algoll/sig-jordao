<?php
/**
 * Created by PhpStorm.
 * User: pauloe.oliveira
 * Date: 26/10/16
 * Time: 15:57
 */

namespace App\CommandBus;

use App\Entity\ParticipanteFolhapagamento;
use App\Repository\ParticipanteFolhapagamentoRepository;
use App\CommandBus\GerarHistoricoFolhaCommand;

class GerarHistoricoFolhaHandler {

    /**
     * @var \App\Repository\ParticipanteFolhapagamentoRepository
     */
    private $participanteFolhaPgtoRepository;

    /**
     * @param ParticipanteFolhapagamentoRepository $participanteFolhaPgto
     */
    public function __construct(ParticipanteFolhapagamentoRepository $participanteFolhaPgtoRepository)
    {
        $this->participanteFolhaPgtoRepository = $participanteFolhaPgtoRepository;
    }

    /**
     * @param GerarHistoricoFolhaCommand $command
     */
    public function handle(GerarHistoricoFolhaCommand $command)
    {
        foreach ($command->getParticipantes() as $participante) {
            $participanteFolhaPgto = new ParticipanteFolhapagamento($participante['projetoPessoa'], $participante['folhaPagamento']);
            $this->participanteFolhaPgtoRepository->add($participanteFolhaPgto);
        }
    }

} 