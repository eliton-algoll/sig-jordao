<?php

namespace App\Repository;

use App\Entity\TramitacaoFormulario;
use App\Entity\SituacaoTramiteFormulario;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\EnvioFormularioAvaliacaoAtividade;
use Symfony\Component\HttpFoundation\ParameterBag;

class TramitacaoFormularioRepository extends RepositoryAbstract
{
    use \App\Traits\MaskTrait;    
    
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TramitacaoFormulario::class);
    }
    
    private $baseFields = [
        'tf.coSeqTramitacaoFormulario',
        'p.noPessoa',
        'pf.nuCpf',
        'prog.dsPrograma',
        'pub.dsPublicacao',
        'pub.dtPublicacao',
        'proj.nuSipar',
        'efa.dtInclusao as dtEnvio',
        'tf.dtInclusao',
        'stf.noSituacaoTramiteFormulario',
        'perf.dsPerfil',
    ];
    
    /**
     * 
     * @return \Doctrine\ORM\QueryBuilder
     */
    private function baseQueryBuilder()
    {
        $qb = $this->createQueryBuilder('tf');
        
        $qb->innerJoin('tf.envioFormularioAvaliacaoAtividade', 'efa')
            ->innerJoin('efa.formularioAvaliacaoAtividade', 'faa')            
            ->innerJoin('tf.situacaoTramiteFormulario', 'stf')
            ->innerJoin('tf.projetoPessoa', 'pp')            
            ->innerJoin('pp.pessoaPerfil', 'pperf')
            ->innerJoin('pperf.perfil', 'perf')
            ->innerJoin('pperf.pessoaFisica', 'pf')
            ->innerJoin('pf.pessoa', 'p')
            ->innerJoin('pp.projeto', 'proj')
            ->innerJoin('proj.publicacao', 'pub')
            ->innerJoin('pub.programa', 'prog')            
            ->select(implode($this->baseFields, ','));
        
        return $qb;
    }
    
    /**
     * 
     * @param ParameterBag $pb
     * @return \Doctrine\ORM\Query
     */
    public function search(ParameterBag $pb)
    {
        $qb = $this->baseQueryBuilder()
            ->select('tf')
            ->where('tf.stRegistroAtivo = :stAtivo')
            ->andWhere('faa.stRegistroAtivo = :stAtivo')
            ->andWhere('efa.stRegistroAtivo = :stAtivo')
            ->setParameter('stAtivo', 'S')
        ;
        
        if ($pb->get('formularioAvaliacaoAtividade')) {
            $qb->andWhere('faa.coSeqFormAvaliacaoAtivd = :formularioAvaliacaoAtividade')
                ->setParameter('formularioAvaliacaoAtividade', $pb->get('formularioAvaliacaoAtividade'));
        }
        if ($pb->get('situacaoTramiteFormulario')) {
            $qb->andWhere('tf.situacaoTramiteFormulario = :situacaoTramiteFormulario')
                ->setParameter('situacaoTramiteFormulario', $pb->get('situacaoTramiteFormulario'));
        }
        if ($pb->get('perfil')) {
            $qb->andWhere('pperf.perfil = :perfil')
                ->setParameter('perfil', $pb->get('perfil'));
        }
        if ($pb->get('publicacao')) {
            $qb->andWhere('proj.publicacao = :publicacao')
                ->setParameter('publicacao', $pb->get('publicacao'));
        }
        if ($pb->get('nuSipar')) {
            $qb->andWhere('proj.nuSipar LIKE :nuSipar')
                ->setParameter('nuSipar', $pb->get('nuSipar') . '%');
        }
        if ($pb->get('noPessoa')) {
            $qb->andWhere('UPPER(p.noPessoa) LIKE UPPER(:noPessoa)')
                ->setParameter('noPessoa', $pb->get('noPessoa') . '%');
        }
        if ($pb->get('nuCpf')) {
            $qb->andWhere('pf.nuCpf LIKE :nuCpf')
                ->setParameter('nuCpf', $this->unmask($pb->get('nuCpf')) . '%');
        }
        if ($pb->get('projetoPessoa')) {
            $qb->andWhere('pp.coSeqProjetoPessoa = :projetoPessoa')
                ->setParameter('projetoPessoa', $pb->get('projetoPessoa'));
        }
        if ($pb->get('anoEnvio')) {
            $start = \DateTime::createFromFormat('d/m/Y', '01/01/' . $pb->get('anoEnvio'));
            $end = \DateTime::createFromFormat('d/m/Y', '31/12/' . $pb->get('anoEnvio'));
            
            $qb->andWhere('efa.dtInclusao BETWEEN :start AND :end')
                ->setParameter('start', $start)
                ->setParameter('end', $end);
        }
        
        $this->orderPagination($qb, $pb);
        
        return $qb->getQuery();
    }
    
    /**
     * 
     * @param TramitacaoFormulario $tramitacaoFormulario
     * @return array<\App\Entity\TramitacaoFormulario>
     */
    public function findHistorioById(TramitacaoFormulario $tramitacaoFormulario)
    {
        return $this->findBy([
            'projetoPessoa' => $tramitacaoFormulario->getProjetoPessoa()->getCoSeqProjetoPessoa(),
            'envioFormularioAvaliacaoAtividade' => $tramitacaoFormulario->getEnvioFormularioAvaliacaoAtividade()->getCoSeqEnvioFormAvalAtivid(),
            'stRegistroAtivo' => 'N',
        ], ['dtInclusao' => 'ASC']);
    }    
    
    /**
     * 
     * @param EnvioFormularioAvaliacaoAtividade $envioFormularioAvaliacaoAtividade
     * @return \Doctrine\ORM\Query
     */
    public function findPendentes(EnvioFormularioAvaliacaoAtividade $envioFormularioAvaliacaoAtividade)
    {
        $query = $this
            ->baseQueryBuilder()            
            ->where('efa.coSeqEnvioFormAvalAtivid = ?0')
            ->andWhere('tf.stRegistroAtivo = ?1')
            ->andWhere('tf.situacaoTramiteFormulario <> ?2')
            ->setParameters([
                $envioFormularioAvaliacaoAtividade->getCoSeqEnvioFormAvalAtivid(),
                'S',
                SituacaoTramiteFormulario::FINALIZADO,
            ])->getQuery();
        
        $query->setHydrationMode(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        
        return $query;
    }
    
    /**
     * 
     * @param EnvioFormularioAvaliacaoAtividade $envioFormularioAvaliacaoAtividade
     * @return \Doctrine\ORM\Query
     */
    public function findEnviados(EnvioFormularioAvaliacaoAtividade $envioFormularioAvaliacaoAtividade)
    {
        $query = $this
            ->baseQueryBuilder()            
            ->where('efa.coSeqEnvioFormAvalAtivid = ?0')
            ->andWhere('tf.stRegistroAtivo = ?1')            
            ->setParameters([
                $envioFormularioAvaliacaoAtividade->getCoSeqEnvioFormAvalAtivid(),
                'S',                
            ])->getQuery();
        
        $query->setHydrationMode(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        
        return $query;
    }
    
    /**
     * 
     * @param EnvioFormularioAvaliacaoAtividade $envioFormularioAvaliacaoAtividade
     * @param SituacaoTramiteFormulario $situacaoTramiteFormulario
     * @return \Doctrine\ORM\Query
     */
    public function findBySituacao(
        EnvioFormularioAvaliacaoAtividade $envioFormularioAvaliacaoAtividade,
        SituacaoTramiteFormulario $situacaoTramiteFormulario
    ) {
        $query = $this
            ->baseQueryBuilder()            
            ->where('efa.coSeqEnvioFormAvalAtivid = ?0')
            ->andWhere('tf.stRegistroAtivo = ?1')
            ->andWhere('stf.coSeqSituacaoTramiteForm = ?2')
            ->setParameters([
                $envioFormularioAvaliacaoAtividade->getCoSeqEnvioFormAvalAtivid(),
                'S',
                $situacaoTramiteFormulario->getCoSeqSituacaoTramiteForm(),
            ])->getQuery();
        
        $query->setHydrationMode(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        
        return $query;
    }
}
