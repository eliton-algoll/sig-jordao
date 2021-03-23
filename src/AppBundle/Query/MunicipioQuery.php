<?php

namespace AppBundle\Query;

use AppBundle\Repository\MunicipioRepository;
use AppBundle\Entity\Uf;

class MunicipioQuery
{
    /**
     * @var MunicipioRepository
     */
    private $municipioRepository;
    
    /**
     * @param MunicipioRepository $municipioRepository
     */
    public function __construct(MunicipioRepository $municipioRepository)
    {
        $this->municipioRepository = $municipioRepository;
    }
    
    /**
     * @param Uf $uf
     * @return array
     */
    public function listByUf(Uf $uf)
    {
        return $this->municipioRepository->findByUf($uf);
    }
    
    /**
     * 
     * @param type $id
     * @return \AppBundle\Entity\Municipio|null
     */
    public function getMunicipioById($id)
    {
        return $this->municipioRepository->find($id);
    }
}
