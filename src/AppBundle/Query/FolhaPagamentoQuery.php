<?php

namespace AppBundle\Query;

use Symfony\Component\HttpFoundation\ParameterBag;
use Knp\Component\Pager\PaginatorInterface;
use AppBundle\Entity\Publicacao;
use AppBundle\Entity\Projeto;
use AppBundle\Entity\SituacaoFolha;
use AppBundle\Entity\FolhaPagamento;
use AppBundle\Repository\ProjetoFolhaPagamentoRepository;
use AppBundle\Repository\FolhaPagamentoRepository;

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
     * @return \AppBundle\Entity\ProjetoFolhaPagamento[]
     */
    public function listProjetoFolhaPagamento(Projeto $projeto)
    {
        return $this->projetoFolhaPagamentoRepository->findBy(array('stRegistroAtivo' => 'S', 'projeto' => $projeto->getCoSeqProjeto()), array('dtInclusao' => 'DESC'));
    }
    
    /**
     * 
     * @param Projeto $projeto
     * @return \AppBundle\Entity\ProjetoFolhaPagamento[]
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
     * @return \AppBundle\Entity\ProjetoFolhaPagamento
     */
    public function findProjetoFolhaPagamento(Projeto $projeto, FolhaPagamento $folha)
    {
        return $this->projetoFolhaPagamentoRepository->findByProjetoAndFolha($projeto, $folha);
    }
    
    /**
     * @param Publicacao $publicacao
     * @return \AppBundle\Entity\FolhaPagamento
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
     * @return \AppBundle\Entity\FolhaPagamento
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
     * @return \AppBundle\Entity\FolhaPagamento
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
