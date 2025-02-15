<?php

namespace App\Controller;

use App\CommandBus\SalvarEmissaoCertificadoCommand;
use App\Entity\ModeloCertificado;
use App\Form\DadosEmissaoCertificadoType;
use App\Form\FiltroGerarCertificadoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormError;

/**
 * @Security("is_granted('COORDENADOR_PROJETO')")
 */
final class CertificadoController extends ControllerAbstract
{
    /**
     * 
     * @param Request $request
     * @return Response
     * 
     * @Route("certificado", name="certificado")
     * @Method({"GET"})
     */
    public function index(Request $request)
    {
        $pagination = null;
        $form = $this->createForm(FiltroGerarCertificadoType::class, null, [
            'projeto' => $this->getProjetoAutenticado(),
        ]);        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $request->query->add($request->query->get('filtro_gerar_certificado'));
            $request->query->set('coProjeto', $this->getProjetoAutenticado()->getCoSeqProjeto());
            $pagination = $this->get('app.participante_query')->searchAll($request->query);
        }
        
        return $this->render('certificado/index.html.twig', [
            'form' => $form->createView(),
            'pagination' => $pagination,
        ]);
    }
    
    /**
     * 
     * @param Request $request
     * @return Response
     * 
     * @Route("certificado/dados-emissao", name="certificado_dados_emissao", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function dadosEmissao(Request $request)
    {
        $response = new \stdClass();        
        $response->isValid = false;
        $response->content = null;
        
        $command = new SalvarEmissaoCertificadoCommand(
            $this->getPessoaPerfilAutenticado()->getProjetoPessoa($this->getProjetoAutenticado())
        );
        
        $form = $this->createForm(DadosEmissaoCertificadoType::class, $command, $request->request->all());
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            try {                
                $this->getBus()->handle($command);
                $response->isValid = true;
            } catch (\Exception $e) {
                $form->addError(new FormError('Ocorreu um erro na execução.'));
                $response->error = $e->getMessage();
            }
        }
        
        $response->content = $this->render('certificado/dados_emissao.html.twig', [
            'form' => $form->createView(),
            'command' => $command,
        ])->getContent();
        
        return new JsonResponse($response);
    }
    
    /**
     * @param Request $request
     * @return Response
     * 
     * @Route("certificado/generate", name="certificado_generate", options={"expose"=true})
     * @Method({"GET"})
     */
    public function generate(Request $request)
    {
        try {
            $projetoPessoa = $this->get('app.participante_query')->getByProjetoPessoa($request->query->get('id'));
            $municipio = $this->get('app.municipio_query')->getMunicipioById($request->query->get('coMunicipio'));
            $stFinalizacaoContrato = (boolean) $request->query->get('stFinalizacaoContrato');
            $qtCargaHoraria = $request->query->get('qtCargaHoraria');

            $programa = $projetoPessoa->getProjeto()->getPublicacao()->getPrograma();

            $tpDocumento = $stFinalizacaoContrato ? ModeloCertificado::CERTIFICADO : ModeloCertificado::DECLARACAO;

            $modelo = $this->get('app.modelo_certificado_query')->getModeloByProgramaAndTipo($programa, $tpDocumento);

            if (!$modelo) {
                throw new \UnexpectedValueException('Nenhum modelo cadastrado para este programa e tipo de documento.');
            }

            $filename = str_replace(' ', '_', strtolower(
                $projetoPessoa->getPessoaPerfil()->getPessoaFisica()->getPessoa()->getNoPessoa()
            ));

            $fileUploaderFacade = $this->get('app.file_uploader_facade');

            $html = $this->renderView('certificado/novo_certificado.pdf.twig', [
                'title' => $filename,
                'modelo' => $modelo,
                'projetoPessoa' => $projetoPessoa,
                'municipio' => $municipio,
                'qtCargaHoraria' => $qtCargaHoraria,
                'imagemCertificado' => $fileUploaderFacade->convertToBase64($modelo->getNoImagemCertificado()),
                'imagemRodape' => $modelo->getNoImagemRodape() ?
                    $fileUploaderFacade->convertToBase64($modelo->getNoImagemRodape()) : null,
            ]);
            
            $pdfFacade = $this->get('app.wkhtmltopdf_facade');
            return $pdfFacade->generate($html, $filename, [
                'orientation' => 'Landscape'
            ]);
        } catch (\UnexpectedValueException $e) {
            return new Response('<script>alert("' . $e->getMessage() . '"); window.close();</script>');
        } catch (\Exception $e) {
            $logger = $this->get('logger');
            $logger->error($e->getMessage());
            return new Response('<script>window.close();</script>');
        }
    }
}
