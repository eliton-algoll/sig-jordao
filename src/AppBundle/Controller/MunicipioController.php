<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Uf;

/**
 * @Security("has_role('ROLE_USER')")
 */
class MunicipioController extends ControllerAbstract
{
    /**
     * @Route("/municipio/{uf}", name="municipio_get_by_uf", options={"expose"=true})
     * @return JsonResponse
     */
    public function getByUfAction(Request $request, Uf $uf)
    {
        return new JsonResponse($this->get('app.municipio_query')->listByUf($uf));
    }
}