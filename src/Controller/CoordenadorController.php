<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Publicacao;
use App\Entity\Perfil;

final class CoordenadorController extends ControllerAbstract
{
    /**
     * @Route("coordenador/list-by-publicacao/{publicacao}", name="coordenador_list_by_publicacao", options={"expose"=true})
     * 
     * @param Publicacao $publicacao
     * @return JsonResponse
     */
    public function listByPublicacao(Publicacao $publicacao)
    {
        $coordenadores = $this->get('app.pessoa_fisica_repository')->listByPublicacaoAndPerfil($publicacao, Perfil::PERFIL_COORDENADOR_PROJETO);
        
        return new JsonResponse(array_map(function ($pessoaFisica) {
            return [                
                'nuCpf' => $pessoaFisica->getNuCpf(),
                'noPessoa' => $pessoaFisica->getPessoa()->getNoPessoa(),                
            ];
        }, $coordenadores));
    }
}
