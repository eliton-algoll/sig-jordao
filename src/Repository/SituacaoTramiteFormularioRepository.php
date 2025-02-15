<?php

namespace App\Repository;

use App\Entity\AgenciaBancaria;
use App\Repository\RepositoryAbstract;
use App\Entity\SituacaoTramiteFormulario;
use Doctrine\Persistence\ManagerRegistry;

class SituacaoTramiteFormularioRepository extends RepositoryAbstract
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AgenciaBancaria::class);
    }
    
    /**
     * 
     * @return SituacaoTramiteFormulario
     */
    public function getPendente()
    {
        return $this
            ->getEntityManager()
            ->getPartialReference(
                SituacaoTramiteFormulario::class,
                SituacaoTramiteFormulario::PENDENTE
            );
    }
    
    /**
     * 
     * @return SituacaoTramiteFormulario
     */
    public function getFinalizado()
    {
        return $this
            ->getEntityManager()
            ->getPartialReference(
                SituacaoTramiteFormulario::class,
                SituacaoTramiteFormulario::FINALIZADO
            );
    }
    
    /**
     * 
     * @return SituacaoTramiteFormulario
     */
    public function getAguardandoAnalise()
    {
        return $this
            ->getEntityManager()
            ->getPartialReference(
                SituacaoTramiteFormulario::class,
                SituacaoTramiteFormulario::AGUARDANDO_ANALISE
            );
    }
}
