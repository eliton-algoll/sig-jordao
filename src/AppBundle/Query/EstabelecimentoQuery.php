<?php

namespace AppBundle\Query;

use AppBundle\WebServices\Cnes;
use AppBundle\Repository\ProjetoRepository;

class EstabelecimentoQuery
{
    /**
     * @var ProjetoRepository
     */
    private $projetoRepository;
    
    /**
     * @var Cnes
     */
    private $wsCnes;
    
    /**
     * @param ProjetoRepository $projetoRepository
     */
    public function __construct(ProjetoRepository $projetoRepository)
    {
        $this->projetoRepository = $projetoRepository;
//        $this->wsCnes = $wsCnes;
    }
    
    /**
     * @param integer $coProjeto
     * @return array
     */
    public function listEstabelecimentos($coProjeto)
    {
        $projeto = $this->projetoRepository->find($coProjeto);
        
        $result = array();
        
        foreach ($projeto->getEstabelecimentosAtivos() as $estabelecimento) {
//            
//            $data = $this
//                ->wsCnes
//                ->consultarEstabelecimentoSaude($estabelecimento->getCoCnes())
//                ->ResultadoPesquisaEstabelecimentoSaude
//                ->EstabelecimentoSaude;
//            
//            $result[] = array(
//                'coProjetoEstabelecimento' => $estabelecimento->getCoSeqProjetoEstabelec(),
//                'coCnes' => $data->CodigoCNES->codigo,
//                'noEstabelecimento' => $data->nomeFantasia->Nome
//            );
            
            $result[] = array(
                'coProjetoEstabelecimento' => $estabelecimento->getCoSeqProjetoEstabelec(),
                'coCnes' => $estabelecimento->getCoCnes(),
                'noEstabelecimento' => ''
            );
        }
        
        return $result;
    }
}