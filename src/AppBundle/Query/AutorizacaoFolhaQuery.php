<?php

namespace AppBundle\Query;

use Knp\Component\Pager\Paginator;
use AppBundle\Repository\AutorizacaoFolhaRepository;
use AppBundle\Entity\FolhaPagamento;
use AppBundle\Entity\SituacaoFolha;

class AutorizacaoFolhaQuery 
{
    
    /**
     * @var AutorizacaoFolhaRepository
     */
    protected $autorizacaoFolhaRepository;

    /**
     * @param Paginator $paginator
     * @param AutorizacaoFolhaRepository $autorizacaoFolhaRepository
     */
    public function __construct(
        AutorizacaoFolhaRepository $autorizacaoFolhaRepository
    ) {
        $this->autorizacaoFolhaRepository = $autorizacaoFolhaRepository;
    }

    /**
     * @param FolhaPagamento $folhaPagamento
     * @return array
     */
    public function getAllByFolha(FolhaPagamento $folhaPagamento) {
        return $this->autorizacaoFolhaRepository->getByFolhaAndStatus($folhaPagamento);
    }
}