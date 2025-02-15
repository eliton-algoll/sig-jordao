<?php

namespace App\Exception;

use App\Entity\ProjetoPessoa;

final class FolhaSuplementarException extends \Exception
{
    const EMPTY_FOLHA = 0;
    const ON_REMOVE_ALL = 1;

    /**
     * @return FolhaSuplementarException
     */
    public static function emptyFolha()
    {
        return new static(
            'É obrigatório que adicione pelo menos um participante na lista de participantes para a Folha de Pagamento Complementar. Operação não permitida.',
            self::EMPTY_FOLHA
        );
    }

    /**
     * @return FolhaSuplementarException
     */
    public static function onRemoveAllParticipantes()
    {
        return new static(
            'Não poderão ser excluídos todos os participantes da folha suplementar. Você poderá cancelar a respectiva folha. Operação não permitida.',
            self::ON_REMOVE_ALL
        );
    }

    /**
     * @param ProjetoPessoa $projetoPessoa
     * @return FolhaSuplementarException
     */
    public static function onParticipanteBelongsToAnotherPublicacao(ProjetoPessoa $projetoPessoa)
    {
        return new static(
            sprintf(
                'O participante %s não pertence a publicação selecionado.',
                $projetoPessoa->getPessoaPerfil()->getPessoaFisica()->getPessoa()->getNoPessoa()
            )
        );
    }

    /**
     * @param ProjetoPessoa $projetoPessoa
     * @return FolhaSuplementarException
     */
    public static function onParticipanteIsVoluntario(ProjetoPessoa $projetoPessoa)
    {
        return new static(
            sprintf(
                'O participante %s é voluntário.',
                $projetoPessoa->getPessoaPerfil()->getPessoaFisica()->getPessoa()->getNoPessoa()
            )
        );
    }

    /**
     * @param ProjetoPessoa $projetoPessoa
     * @return FolhaSuplementarException
     */
    public static function onParticipanteInativo(ProjetoPessoa $projetoPessoa)
    {
        return new static(
            sprintf(
                'O participante %s está inativo.',
                $projetoPessoa->getPessoaPerfil()->getPessoaFisica()->getPessoa()->getNoPessoa()
            )
        );
    }
}
