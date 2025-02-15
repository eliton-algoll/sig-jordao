<?php

namespace App\Query;

use Symfony\Component\HttpFoundation\ParameterBag;
use Knp\Component\Pager\Paginator;
use App\Repository\ProjetoRepository;
use App\Repository\ProjetoPessoaRepository;
use App\Entity\PessoaPerfil;

class ProjetoQuery
{
    /**
     * @var ProjetoPessoaRepository
     */
    private $projetoPessoaRepository;
    
    /**
     * @var ProjetoRepository
     */
    private $projetoRepository;
    
    /**
     * @var Paginator
     */
    private $paginator;
    
    /**
     * @param Paginator $paginator
     * @param ProjetoPessoaRepository repository
     * @param ProjetoRepository $projetoRepository
     */
    public function __construct($paginator, $projetoPessoaRepository, $projetoRepository)
    {
        $this->projetoPessoaRepository = $projetoPessoaRepository;
        $this->projetoRepository = $projetoRepository;
        $this->paginator = $paginator;
    }
    
    /**
     * @param ParameterBag $params
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function search(ParameterBag $params)
    {
        return $this->paginator->paginate(
            $this->projetoRepository->search($params),
            $params->getInt('page', 1),
            $params->getInt('limit', 10)
        );
    }
    
    /**
     * @param integer $coProjeto
     * @return \App\Entity\Projeto | null
     */
    public function findById($coProjeto)
    {
        return $this->projetoRepository->find($coProjeto);
    }

    /**
     * @param integer $coProjeto
     * @return \App\Entity\Projeto | null
     */
    public function findParticipanteOrientadorByProjeto($coProjeto, $coPerfis, $cpf)
    {
        return $this->projetoRepository->findParticipanteOrientadorByProjeto($coProjeto, $coPerfis, $cpf);
    }

    /**
     * @param integer $coProjeto
     * @return \App\Entity\Projeto | null
     */
    public function countParticipanteCadastradoByProjetoAndGrupo($coProjeto, $coPerfil, $coGrupo, $cpf)
    {
        return $this->projetoRepository->findParticipantesByProjetoAndPefilAndGroup($coProjeto, $coPerfil, $coGrupo, $cpf);
    }

    /**
     * @param integer $coProjeto
     * @return \App\Entity\Projeto | null
     */
    public function countNrGruposByProjeto($coProjeto)
    {
        return $this->projetoRepository->getNrGrupos($coProjeto);
    }

    /**
     * @param integer $coProjeto
     * @return \App\Entity\Projeto | null
     */
    public function getNrGruposComParticpantesPorProjeto($coProjeto)
    {
        return $this->projetoRepository->getNrGruposComParticpantes($coProjeto);
    }

    /**
     * @param integer $coProjeto
     * @return \App\Entity\Projeto | null
     */
    public function getEixosComParticpantes($coProjeto)
    {
        return $this->projetoRepository->getEixosComParticpantes($coProjeto);
    }
    
    /**
     * @param PessoaPerfil $pessoaPerfil
     * @return array
     */
    public function listProjetosAutorizados(PessoaPerfil $pessoaPerfil)
    {
        $projetos = $pessoaPerfil->getProjetos();
        
        $result = array();
        
        foreach ($projetos as $projeto) {
            $result[] = array(
                'coSeqProjeto' => $projeto->getCoSeqProjeto(),
                'dsPrograma' => $projeto->getPublicacao()->getPrograma()->getDsPrograma(),
                'nuSipar' => $projeto->getNuSipar(),
                'dsPublicacao' => $projeto->getPublicacao()->getDescricaoCompleta(false)
            );
        }
        
        return $result;
    }
    
    public function searchRelatorioPagamento(ParameterBag $params)
    {
        return $this->paginator->paginate(
            $this->projetoRepository->searchRelatorioPagamento($params),
            $params->getInt('page', 1),
            $params->getInt('limit', 10)
        );
    }
    
    public function searchRelatorioProjeto(ParameterBag $params)
    {
        return $this->projetoRepository->searchRelatorioProjeto($params);
    }
    
    /**
     * 
     * @param string $nuSipar
     * @return \App\Entity\Projeto|null
     * @throws SiparInvalidoException
     */
    public function getBySipar($nuSipar)
    {
        return $this->projetoRepository->getBySipar($nuSipar);
    }
}