<?php

namespace App\Query;

Use App\Entity\AgenciaBancaria;
use App\Entity\DadoPessoal;
use App\Repository\AgenciaBancariaRepository;
use App\Repository\AutorizaCadastroParticipanteRepository;
use App\Repository\ProjetoPessoaRepository;
use App\Repository\VwParticipanteRepository;
use Knp\Component\Pager\Paginator;
use Symfony\Component\HttpFoundation\ParameterBag;

class ParticipanteQuery 
{
    /**
     * @var Paginator
     */
    protected $paginator;
    
    /**
     * @var ProjetoPessoaRepository
     */
    protected $projetoPessoaRepository;
    
    /**
     * @var AgenciaBancariaRepository
     */
    protected $agenciaBancariaRepository;
    
    /**
     * @var VwParticipanteRepository
     */
    protected $vwParticipanteRepository;
    
    /**
     *
     * @var AutorizaCadastroParticipanteRepository 
     */
    protected $autorizacaoCadastroParticipanteRepository;

    /**
     * 
     * @param Paginator $paginator
     * @param ProjetoPessoaRepository $projetoPessoaRepository
     * @param AgenciaBancariaRepository $agenciaBancariaRepository
     * @param VwParticipanteRepository $vwParticipanteRepository
     * @param AutorizaCadastroParticipanteRepository $autorizacaoCadastroParticipanteRepository
     */
    public function __construct(
        Paginator $paginator, 
        ProjetoPessoaRepository $projetoPessoaRepository, 
        AgenciaBancariaRepository $agenciaBancariaRepository,
        VwParticipanteRepository $vwParticipanteRepository,
        AutorizaCadastroParticipanteRepository $autorizacaoCadastroParticipanteRepository
    ) {
        $this->paginator = $paginator;
        $this->projetoPessoaRepository = $projetoPessoaRepository;
        $this->agenciaBancariaRepository = $agenciaBancariaRepository;
        $this->vwParticipanteRepository = $vwParticipanteRepository;
        $this->autorizacaoCadastroParticipanteRepository = $autorizacaoCadastroParticipanteRepository;
    }

    /**
     * @param ParameterBag $params
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function search(ParameterBag $params)
    {
        return $this->paginator->paginate(
            $this->projetoPessoaRepository->search($params),
            $params->getInt('page', 1),
            $params->getInt('limit', 10)
        );
    }

    /**
     * @param ParameterBag $params
     * @return array
     */
    public function searchExport(ParameterBag $params){
        $data    = $this->projetoPessoaRepository->search($params);
        $paginator = $this->paginator->paginate(
            $data,
            $params->getInt('page', 1),
            $params->getInt('limit', 10)
        );
        return array('export'    => $data->getQuery()->getResult(),
                     'paginator' => $paginator);
    }
    
//    /**
//     * @param DadoPessoal $dadoPessoal
//     * @return AgenciaBancaria|null
//     */
//    public function getAgenciaBancariaByDadoPessoal(DadoPessoal $dadoPessoal)
//    {
//        return $this->agenciaBancariaRepository->findOneBy(array(
//            'coAgenciaBancaria' => $dadoPessoal->getAgencia()->getCoAgenciaBancaria(),
//            'coBanco' => $dadoPessoal->getBanco()->getCoBanco(),
//            'stRegistroAtivo' => 'S'
//        ));
//    }
    
    /**
     * @param ParameterBag $params
     * @return array
     */
    public function searchRelatorioParticipante(ParameterBag $params)
    {
        return $this->vwParticipanteRepository->searchRelatorioParticipante($params);
    }
    
    /**
     * 
     * @param ParameterBag $pb
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function searchAll(ParameterBag $pb)
    {
        return $this->paginator->paginate(
            $this->vwParticipanteRepository->search($pb),
            $pb->getInt('page', 1),
            $pb->getInt('limit', 10)
        );
    }
    
    /**
     * 
     * @param integer $id
     * @return \App\Entity\ProjetoPessoa
     */
    public function getByProjetoPessoa($id)
    {
        $result = $this->projetoPessoaRepository->find($id);
        
        if (!$result) {
            throw new \UnexpectedValueException('Dado nao encontrado.');
        }
        
        return $result;
    }
    
    /**
     * 
     * @param ParameterBag $pb
     * @return array
     */
    public function searchRelatorioGerencial(ParameterBag $pb)
    {
        return $this->vwParticipanteRepository->searchRelatorioGerencial($pb);
    }
    
    /**
     * 
     * @param ParameterBag $pb
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function searchAberturasCadastroParticipante(ParameterBag $pb)
    {
        return $this->paginator->paginate(
            $this->autorizacaoCadastroParticipanteRepository->search($pb),
            $pb->getInt('page', 1),
            $pb->getInt('limit', 10)
        );
    }
}
