<?php

namespace App\Query;

use Symfony\Component\HttpFoundation\ParameterBag;
use Knp\Component\Pager\PaginatorInterface;
use App\Entity\Publicacao;
use App\Entity\Projeto;
use App\Entity\SituacaoFolha;
use App\Entity\FolhaPagamento;
use App\Repository\ProjetoFolhaPagamentoRepository;
use App\Repository\FolhaPagamentoRepository;

class FolhaPagamentoQuery
{
    /**
     * @var PaginatorInterface
     */
    private $paginator;
    
    /**
     * @var FolhaPagamentoRepository
     */
    private $folhaPagamentoRepository;
    
    /**
     * @var ProjetoFolhaPagamentoRepository
     */
    private $projetoFolhaPagamentoRepository;
    
    /**
     * @param FolhaPagamentoRepository $folhaPagamentoRepository
     * @param ProjetoFolhaPagamentoRepository $projetoFolhaPagamentoRepository
     */
    public function __construct(
        PaginatorInterface $paginator,
        FolhaPagamentoRepository $folhaPagamentoRepository, 
        ProjetoFolhaPagamentoRepository $projetoFolhaPagamentoRepository
    ) {
        $this->paginator = $paginator;
        $this->folhaPagamentoRepository = $folhaPagamentoRepository;
        $this->projetoFolhaPagamentoRepository = $projetoFolhaPagamentoRepository;
    }
    
    /**
     * @param Projeto $projeto
     * @return \App\Entity\ProjetoFolhaPagamento[]
     */
    public function listProjetoFolhaPagamento(Projeto $projeto)
    {
        return $this->projetoFolhaPagamentoRepository->findBy(array('stRegistroAtivo' => 'S', 'projeto' => $projeto->getCoSeqProjeto()), array('dtInclusao' => 'DESC'));
    }
    
    /**
     * 
     * @param Projeto $projeto
     * @return \App\Entity\ProjetoFolhaPagamento[]
     */
    public function listProjetoFolhaPagamentoMensal(Projeto $projeto)
    {
        return $this->projetoFolhaPagamentoRepository->listMensalByProjeto($projeto);
    }

    public function informeRendimento(ParameterBag $params){
        return $this->projetoFolhaPagamentoRepository->informeRendimento($params);
    }

    /**
     * @param Projeto $projeto
     * @param FolhaPagamento $folha
     * @return \App\Entity\ProjetoFolhaPagamento
     */
    public function findProjetoFolhaPagamento(Projeto $projeto, FolhaPagamento $folha)
    {
        return $this->projetoFolhaPagamentoRepository->findByProjetoAndFolha($projeto, $folha);
    }
    
    /**
     * @param Publicacao $publicacao
     * @return \App\Entity\FolhaPagamento
     */
    public function findFolhaAbertaByPublicacao(Publicacao $publicacao)
    {
        return $this->folhaPagamentoRepository->findOneBy(array(
            'publicacao'        => $publicacao->getCoSeqPublicacao(),
            'situacao'          => SituacaoFolha::ABERTA,
            'stRegistroAtivo'   => 'S',
            'tpFolhaPagamento'  => FolhaPagamento::MENSAL,
        ));
    }
    
    /**
     * @param Publicacao $publicacao
     * @return \App\Entity\FolhaPagamento
     */
    public function findFolhaFechadaByPublicacao(Publicacao $publicacao)
    {
        return $this->folhaPagamentoRepository->findOneBy(array(
            'publicacao'        => $publicacao->getCoSeqPublicacao(),
            'situacao'          => SituacaoFolha::FECHADA,
            'stRegistroAtivo'   => 'S',
            'tpFolhaPagamento'  => FolhaPagamento::MENSAL,
        ));
    }
    
    /**
     * @param Publicacao $publicacao
     * @return \App\Entity\FolhaPagamento
     */
    public function findFolhaHomologadaByPublicacao(Publicacao $publicacao)
    {
        return $this->folhaPagamentoRepository->findOneBy(array(
            'publicacao'        => $publicacao->getCoSeqPublicacao(),
            'situacao'          => SituacaoFolha::HOMOLOGADA,
            'stRegistroAtivo'   => 'S',
            'tpFolhaPagamento'  => FolhaPagamento::MENSAL,            
        ));
    }
    
    /**
     * @param ParameterBag $params
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function searchFolhaPagamento(ParameterBag $params)
    {
        return $this->paginator->paginate(
            $this->folhaPagamentoRepository->search($params),
            $params->getInt('page', 1),
            $params->getInt('limit', 10)
        );
    }
    
    public function findProjetos(FolhaPagamento $folha)
    {
        return $this->folhaPagamentoRepository->findProjetos($folha->getCoSeqFolhaPagamento());
    }
    
    public function searchRelatorioPagamentoNaoAutorizado(ParameterBag $params)
    {
        return $this->folhaPagamentoRepository->searchRelatorioPagamentoNaoAutorizado($params);
    }

    /**
     * @param $folhaPagamento
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function findParticipantesFolha($arFolhaPagamento)
    {
        return $this->folhaPagamentoRepository->findParticipantesProjetoFolhaPagamento($arFolhaPagamento);
    }
}
