<?php

namespace AppBundle\Query;

use AppBundle\Entity\ModeloCertificado;
use AppBundle\Repository\ModeloCertificadoRepository;
use Knp\Component\Pager\Paginator;
use Symfony\Component\HttpFoundation\ParameterBag;

final class ModeloCertificadoQuery
{
    /**
     * @var Paginator 
     */
    private $paginator;
    
    /**
     * @var ModeloCertificadoRepository
     */
    private $modeloCertificadoRepository;
    
    /**
     * @param Paginator $paginator
     * @param ModeloCertificadoRepository $modeloCertificadoRepository
     */
    public function __construct(Paginator $paginator, ModeloCertificadoRepository $modeloCertificadoRepository)
    {
        $this->paginator = $paginator;
        $this->modeloCertificadoRepository = $modeloCertificadoRepository;
    }

    /**
     * @param $programa
     * @param $tpDocumento
     * @return ModeloCertificado|object|null
     */
    public function getModeloByProgramaAndTipo($programa, $tpDocumento)
    {
        return $this->modeloCertificadoRepository->findOneBy([
            'programa' => $programa,
            'tpDocumento' => $tpDocumento,
            'stRegistroAtivo' => 'S',
        ]);
    }

    /**
     * @param ParameterBag $pb
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function search(ParameterBag $pb)
    {
        $orderBy = [];
        if ($pb->has('sort')) {
            $orderBy[$pb->get('sort')] = $pb->get('direction', 'ASC');
        }
        return $this->paginator->paginate(
            $this->modeloCertificadoRepository->buildSearchQuery(
                $pb->get('filtro_modelo_certificado', []),
                $orderBy
            ),
            $pb->getInt('page', 1),
            $pb->getInt('limit', 10)
        );
    }
}
