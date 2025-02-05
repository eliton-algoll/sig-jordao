<?php

namespace AppBundle\Query;

use AppBundle\Entity\EnvioFormularioAvaliacaoAtividade;
use AppBundle\Entity\TramitacaoFormulario;
use AppBundle\Repository\EnvioFormularioAvaliacaoAtividadeRepository;
use AppBundle\Repository\FormularioAvaliacaoAtividadeRepository;
use AppBundle\Repository\SituacaoTramiteFormularioRepository;
use AppBundle\Repository\TramitacaoFormularioRepository;
use Knp\Component\Pager\Paginator;
use Symfony\Component\HttpFoundation\ParameterBag;

class AvaliacaoAtividadeQuery
{
    /**
     * @var Paginator
     */
    protected $paginator;
    
    /**
     *
     * @var FormularioAvaliacaoAtividadeRepository
     */
    private $formularioAvaliacaoAtividadeRepository;
    
    /**
     *
     * @var EnvioFormularioAvaliacaoAtividadeRepository 
     */
    private $envioFormularioAvaliacaoAtividadeRepository;
    
    /**
     *
     * @var TramitacaoFormularioRepository 
     */
    private $tramitacaoFormularioRepository;
    
    /**
     *
     * @var SituacaoTramiteFormularioRepository 
     */
    private $situcaoTramiteFormulario;
    
    /**
     * 
     * @param Paginator $paginator
     * @param FormularioAvaliacaoAtividadeRepository $formularioAvaliacaoAtividadeRepository
     * @param EnvioFormularioAvaliacaoAtividadeRepository $envioFormularioAvaliacaoAtividadeRepository
     * @param TramitacaoFormularioRepository $tramitacaoFormularioRepository
     */
    public function __construct(
        Paginator $paginator,
        FormularioAvaliacaoAtividadeRepository $formularioAvaliacaoAtividadeRepository,
        EnvioFormularioAvaliacaoAtividadeRepository $envioFormularioAvaliacaoAtividadeRepository,
        TramitacaoFormularioRepository $tramitacaoFormularioRepository,
        SituacaoTramiteFormularioRepository $situacaoTramiteFormulario
    ) {
        $this->paginator = $paginator;
        $this->formularioAvaliacaoAtividadeRepository = $formularioAvaliacaoAtividadeRepository;
        $this->envioFormularioAvaliacaoAtividadeRepository = $envioFormularioAvaliacaoAtividadeRepository;
        $this->tramitacaoFormularioRepository = $tramitacaoFormularioRepository;
        $this->situcaoTramiteFormulario = $situacaoTramiteFormulario;
    }

    /**
     * 
     * @param ParameterBag $pb
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function searchFormularios(ParameterBag $pb)
    {
        return $this->paginator->paginate(
            $this->formularioAvaliacaoAtividadeRepository->search($pb),
            $pb->getInt('page', 1),
            $pb->getInt('limit', 10)
        );        
    }
    
    /**
     * 
     * @param ParameterBag $pb
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function searchEnvios(ParameterBag $pb)
    {
        return $this->paginator->paginate(
            $this->envioFormularioAvaliacaoAtividadeRepository->searchEnvio($pb),
            $pb->getInt('page', 1),
            $pb->getInt('limit', 10)
        );
    }
    
    /**
     * 
     * @param ParameterBag $pb
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function searchRetornos(ParameterBag $pb)
    {
        return $this->paginator->paginate(
            $this->tramitacaoFormularioRepository->search($pb),
            $pb->getInt('page', 1),
            $pb->getInt('limit', 10)
        );
    }
    
    /**
     * 
     * @param TramitacaoFormulario $tramitacaoFormulario
     * @return array<\AppBundle\Entity\TramitacaoFormulario>
     */
    public function findHistoricoTramitacaoFormulario(TramitacaoFormulario $tramitacaoFormulario)
    {
        return $this->tramitacaoFormularioRepository->findHistorioById($tramitacaoFormulario);
    }
    
    /**
     * 
     * @param EnvioFormularioAvaliacaoAtividade $envioFormularioAvaliacaoAtividade
     * @param ParameterBag $pb
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function searchPendentes(
        EnvioFormularioAvaliacaoAtividade $envioFormularioAvaliacaoAtividade,
        ParameterBag $pb
    ) {
        return $this->paginator->paginate(
            $this->tramitacaoFormularioRepository->findPendentes($envioFormularioAvaliacaoAtividade),
            $pb->getInt('page', 1),
            $pb->getInt('limit', 10)
        );
    }
    
    /**
     * 
     * @param EnvioFormularioAvaliacaoAtividade $envioFormularioAvaliacaoAtividade
     * @param ParameterBag $pb
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function searchEnviados(
        EnvioFormularioAvaliacaoAtividade $envioFormularioAvaliacaoAtividade,
        ParameterBag $pb
    ) {
        return $this->paginator->paginate(
            $this->tramitacaoFormularioRepository->findEnviados($envioFormularioAvaliacaoAtividade),
            $pb->getInt('page', 1),
            $pb->getInt('limit', 10)
        );
    }
    
    /**
     * 
     * @param EnvioFormularioAvaliacaoAtividade $envioFormularioAvaliacaoAtividade
     * @param ParameterBag $pb
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function searchFinalizados(
        EnvioFormularioAvaliacaoAtividade $envioFormularioAvaliacaoAtividade,        
        ParameterBag $pb
    ) {
        return $this->paginator->paginate(
            $this->tramitacaoFormularioRepository
                ->findBySituacao(
                    $envioFormularioAvaliacaoAtividade,
                    $this->situcaoTramiteFormulario->getFinalizado()
                ),
            $pb->getInt('page', 1),
            $pb->getInt('limit', 10)
        );
    }
}
