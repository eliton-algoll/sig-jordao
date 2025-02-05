<?php
/**
 * Created by PhpStorm.
 * User: pauloe.oliveira
 * Date: 26/10/16
 * Time: 15:57
 */

namespace AppBundle\CommandBus;

use AppBundle\Entity\ParticipanteFolhapagamento;
use AppBundle\Repository\ParticipanteFolhapagamentoRepository;
use AppBundle\CommandBus\GerarHistoricoFolhaCommand;

class GerarHistoricoFolhaHandler {

    /**
     * @var \AppBundle\Repository\ParticipanteFolhapagamentoRepository
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