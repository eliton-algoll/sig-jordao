<?php

namespace AppBundle\Query;

use AppBundle\Repository\PublicacaoRepository;

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
     * @return \AppBundle\Entity\Publicacao[]
     */
    public function findAllAtivos()
    {
        return $this->publicacaoRepository->findBy(array('stRegistroAtivo' => 'S'));
    }
    
    /**
     * 
     * @param array $ids
     * @return \AppBundle\Entity\Publicacao[]
     */
    public function findByIds(array $ids)
    {
        return $this->publicacaoRepository->findBy(['coSeqPublicacao' => $ids]);
    }
}
