<?php

namespace AppBundle\CommandBus;

use AppBundle\Entity\ProjetoPessoaGrupoAtuacao;
use AppBundle\Event\HandleSituacaoGrupoAtuacaoEvent;
use AppBundle\Repository\ProjetoPessoaRepository;
use AppBundle\Repository\UsuarioRepository;
use AppBundle\CommandBus\InativarParticipanteCommand;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class InativarParticipanteHandler
{

    /**
     * @var ProjetoPessoaRepository
     */
    private $projetoPessoaRepository;

    /**
     * @var UsuarioRepository
     */
    private $usuarioRepository;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * InativarParticipanteHandler constructor.
     *
     * @param ProjetoPessoaRepository $projetoPessoaRepository
     * @param UsuarioRepository $usuarioRepository
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        ProjetoPessoaRepository  $projetoPessoaRepository,
        UsuarioRepository        $usuarioRepository,
        EventDispatcherInterface $eventDispatcher
    )
    {
        $this->projetoPessoaRepository = $projetoPessoaRepository;
        $this->usuarioRepository = $usuarioRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function handle(InativarParticipanteCommand $command)
    {
        $projetoPessoa = $command->getProjetoPessoa();

        if ($projetoPessoa->getPessoaPerfil()->getPerfil()->isPreceptor()) { // 4 - Preceptor
            $gruposAtuacoes = $projetoPessoa->getProjetoPessoaGrupoAtuacaoAtivo();
            $gruposAtuacao = array();
            //reordena o index do array para que o registro esteja sempre na posição 0
            foreach ($gruposAtuacoes as $value) {
                $gruposAtuacao[]=$value;
             }
            if ((!is_null($gruposAtuacao)) && (count($gruposAtuacao) > 0)) {
                $grupoAtuacao = $gruposAtuacao[0]->getGrupoAtuacao();
                $eixoAtuacao = $grupoAtuacao->getCoEixoAtuacao();

//                if ($eixoAtuacao === 'A') { // Assistência a Saúde
//                    $cursoGraduacaoPreceptor = $projetoPessoa->getCursoGraduacaoEstudanteOuPreceptor();
                    /*
                    if (!is_null($cursoGraduacaoPreceptor)) {
                        // Obtém todas as pessoas do mesmo grupo
                        // ProjetoPessoaGrupoAtuacao
                        $projetoPessoasGrupoAtuacao = $grupoAtuacao->getProjetoPessoaGrupoAtuacao();

                        foreach ($projetoPessoasGrupoAtuacao as $projetoPessoaGrupoAtuacao) {
                            $projetoPessoaDoGrupo = $projetoPessoaGrupoAtuacao->getProjetoPessoa();

                            // A pessoa precisa estar ativa e ser estudante
                            if (($projetoPessoaDoGrupo->isAtivo()) && ($projetoPessoaDoGrupo->getPessoaPerfil()->getPerfil()->isEstudante())) {
                                $cursoGraduacaoEstudante = $projetoPessoaDoGrupo->getCursoGraduacaoEstudanteOuPreceptor();

                                if ((!is_null($cursoGraduacaoEstudante)) && ($cursoGraduacaoPreceptor->getCoSeqCursoGraduacao() === $cursoGraduacaoEstudante->getCoSeqCursoGraduacao())) {
                                    $this->inativarProjetoPessoa($projetoPessoaDoGrupo);
                                }
                            }
                        }
                    }
                    */
//                }
            }
        }

        $this->inativarProjetoPessoa($projetoPessoa);
    }

    private function inativarProjetoPessoa($projetoPessoa) {
        // O evento tem que presceder a ação para não "desconfirmar" os grupos que o profissional
        // não está mais presente
        $this->eventDispatcher->dispatch(HandleSituacaoGrupoAtuacaoEvent::NAME,
            new HandleSituacaoGrupoAtuacaoEvent($projetoPessoa));

        $projetoPessoa->inativar();
        $projetoPessoa->getPessoaPerfil()->inativar();
        $projetoPessoa->inativarAllDadosAcademicos();
        $projetoPessoa->inativarAllProjetoPessoaCursoGraduacao();
        $projetoPessoa->inativarAllGruposAtuacao();

        $pessoaFisica = $projetoPessoa->getPessoaPerfil()->getPessoaFisica();

        // Se tiver apenas um perfil ativo, todas as informações no sistema serão inativadas
        if ($pessoaFisica->hasOnePessoaPerfilAtivo()) {
            $nuCpf = $pessoaFisica->getPessoa()->getNuCpfCnpjPessoa();
            $usuario = $this->usuarioRepository->findOneBy(array('pessoaFisica' => $nuCpf));
            $usuario->inativar();
            $this->usuarioRepository->add($usuario);
        }

        $this->projetoPessoaRepository->add($projetoPessoa);
    }

}
