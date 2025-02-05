<?php

namespace AppBundle\Repository;

use AppBundle\Entity\SituacaoFolha;

class SituacaoFolhaRepository extends RepositoryAbstract
{
    /**
     * 
     * @return SituacaoFolha
     */
    public function getFinalizada()
    {
        return $this->find(SituacaoFolha::FINALIZADA);
    }
}
