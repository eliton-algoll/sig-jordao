<?php

namespace App\Repository;

use App\Entity\SituacaoFolha;

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
