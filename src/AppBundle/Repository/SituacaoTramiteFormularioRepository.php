<?php

namespace AppBundle\Repository;

use AppBundle\Entity\SituacaoTramiteFormulario;

class SituacaoTramiteFormularioRepository extends RepositoryAbstract
{
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
