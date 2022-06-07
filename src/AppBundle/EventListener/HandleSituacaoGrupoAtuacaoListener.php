<?php

namespace AppBundle\EventListener;

use AppBundle\Event\HandleSituacaoGrupoAtuacaoEvent;
use AppBundle\Repository\GrupoAtuacaoRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class HandleSituacaoGrupoAtuacaoListener implements EventSubscriberInterface
{

    /**
     * @var GrupoAtuacaoRepository
     */
    private $grupoAtuacaoRepository;

    /**
     * HandleSituacaoGrupoAtuacaoListener constructor.
     * @param GrupoAtuacaoRepository $grupoAtuacaoRepository
     */
    public function __construct(GrupoAtuacaoRepository $grupoAtuacaoRepository)
    {
        $this->grupoAtuacaoRepository = $grupoAtuacaoRepository;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            HandleSituacaoGrupoAtuacaoEvent::NAME => 'onCadastraOrInativaParticipante',
        ];
    }

    /**
     * @param HandleSituacaoGrupoAtuacaoEvent $event
     */
    public function onCadastraOrInativaParticipante(HandleSituacaoGrupoAtuacaoEvent $event)
    {
        $projetoPessoa = $event->getProjetoPessoa();

        if (
            $projetoPessoa->getProjeto()->getPublicacao()->getPrograma()->isAreaAtuacao() ||
            $projetoPessoa->getProjetoPessoaGrupoAtuacaoAtivo()->isEmpty()
        ) {
            return;
        }

        foreach ($projetoPessoa->getProjetoPessoaGrupoAtuacaoAtivo() as $projetoPessoaGrupoAtuacao) {
            $grupoAtuacao = $projetoPessoaGrupoAtuacao->getGrupoAtuacao();
            $grupoAtuacao->desconfirmar();
            $this->grupoAtuacaoRepository->add($grupoAtuacao);
        }
    }

}