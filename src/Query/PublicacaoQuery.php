<?php

namespace App\Query;

use App\Repository\PublicacaoRepository;

class PublicacaoQuery
{
    /**
     * @var PublicacaoRepository
     */
    private $publicacaoRepository;
    
    /**
     * @param PublicacaoRepository $publicacaoRepository
     */
    public function __construct($publicacaoRepository)
    {
        $this->publicacaoRepository = $publicacaoRepository;
    }
    
    /**
     * @return \App\Entity\Publicacao[]
     */
    public function findAllAtivos()
    {
        return $this->publicacaoRepository->findBy(array('stRegistroAtivo' => 'S'));
    }
    
    /**
     * 
     * @param array $ids
     * @return \App\Entity\Publicacao[]
     */
    public function findByIds(array $ids)
    {
        return $this->publicacaoRepository->findBy(['coSeqPublicacao' => $ids]);
    }
}
