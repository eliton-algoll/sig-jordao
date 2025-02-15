<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Projeto;

class AreaTematicaController extends ControllerAbstract
{
    /**
     * @Route("/area_tematica/{projeto}", name="area_tematica_get_by_projeto", options={"expose"=true})
     * @return JsonResponse
     */
    public function getByProjetoAction(Request $request, Projeto $projeto)
    {
        return new JsonResponse($this->get('app.area_tematica_query')->listTipoAreaTematicaByProjeto($projeto));
    }
}