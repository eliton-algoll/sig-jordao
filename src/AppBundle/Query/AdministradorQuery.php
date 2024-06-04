<?php

namespace AppBundle\Query;

use AppBundle\Repository\BancoRepository;
use AppBundle\Repository\UsuarioRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\Paginator;
use Symfony\Component\HttpFoundation\ParameterBag;

final class AdministradorQuery
{
    /**
     *
     * @var Paginator 
     */
    private $paginator;
    
    /**
     *
     * @var UsuarioRepository
     */
    private $usuarioRepository;
    
    /**
     * 
     * @param Paginator $paginator
     * @param UsuarioRepository $usuarioRepository
     */
    public function __construct(Paginator $paginator, UsuarioRepository $usuarioRepository)
    {
        $this->paginator = $paginator;
        $this->usuarioRepository = $usuarioRepository;
    }

    /**
     * 
     * @param ParameterBag $pb
     * @return PaginationInterface
     */
    public function search(ParameterBag $pb)
    {
        return $this->paginator->paginate(
            $this->usuarioRepository->findByFilter($pb),
            $pb->getInt('page', 1),
            $pb->getInt('limit', 10)
        );
    }

}
