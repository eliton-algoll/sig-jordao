<?php
/**
 * Created by PhpStorm.
 * User: pauloe.oliveira
 * Date: 20/03/17
 * Time: 10:59
 */

namespace App\Controller;

use App\CommandBus\ConsultarInformeRendimentoCommand;
use App\Controller\ControllerAbstract;
use App\Entity\ProjetoPessoa;
use Doctrine\Common\Util\Debug;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\NoResultException;
use App\Form\ConsultarInformeRendimentoType;

class InformeRendimentoController extends ControllerAbstract
{
    /**
     * @Route("/informe-rendimento", name="informe-rendimento")
     * @param $request Request
     * @return Response
     */
    public function indexInformeRendimentoAction(Request $request)
    {

        $requestParams = $request->request->get('consultar_informe_rendimento', array());
        $params = new ParameterBag($requestParams);
        $pagination = array();

        $command = new ConsultarInformeRendimentoCommand();

        $form = $this->createForm(ConsultarInformeRendimentoType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $validator = $this->get('validator');
            $errors = $validator->validate($command);

            if ($errors->count() == 0) {
                $pagination = $this->get('app.folha_pagamento_query')->informeRendimento($params);

                if(empty($pagination)){
                    $this->addFlash('danger', 'Informe de rendimento não encontrado, favor verificar os dados informados.');
                }
            }
        }

        return $this->render(
            'informe_rendimento/consultarInformeRendimento.html.twig',
            array(
                'pagination' => $pagination,
                'requestParams' => $requestParams,
                'form' => $form->createView()
            )
        );
    }

    /**
     * @Route("/informe-rendimento/baixar-informe/{dadosParticipante}", name="baixar-informe-rendimento")
     * @param Request $request
     * @return type
     */
    public function baixarInformeRendimentoAction(Request $request, $dadosParticipante)
    {
        $arDadosParticipante = explode('-', $dadosParticipante);

        $keys = array('projetoPessoa','publicacao','nuAnoBase');
        $arDadosParticipante = array_combine($keys, $arDadosParticipante);
        $params = new ParameterBag($arDadosParticipante);

        $dadosInformeRendimento = current($this->get('app.folha_pagamento_query')->informeRendimento($params));

        return $this->render(
            'informe_rendimento/informeRendimento.html.twig',
            array(
                'dadosInformeRendimento' => $dadosInformeRendimento
            )
        );
    }
}
