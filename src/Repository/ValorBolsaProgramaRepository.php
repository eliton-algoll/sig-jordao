<?php

namespace App\Repository;

use App\Entity\Perfil;
use App\Entity\Publicacao;
use App\Entity\AgenciaBancaria;
use App\Entity\ValorBolsaPrograma;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\ParameterBag;
use App\Exception\ValorBolsaProgramaExistsException;

class ValorBolsaProgramaRepository extends RepositoryAbstract
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AgenciaBancaria::class);
    }
    
    /**
     * 
     * @param Publicacao $publicacao
     * @param Perfil $perfil
     * @param string $nuMes
     * @param string $nuAno
     * @throws \UnexpectedValueException
     */
    public function checkExists(
        Publicacao $publicacao,
        Perfil $perfil,
        $nuMes,
        $nuAno,
        ValorBolsaPrograma $valorBolsaPrograma = null
    ) {
        $qb = $this->createQueryBuilder('vlb')
            ->where('vlb.publicacao = :publicacao')
            ->andWhere('vlb.perfil = :perfil')
            ->andWhere('vlb.nuMesVigencia = :nuMes')
            ->andWhere('vlb.nuAnoVigencia = :nuAno')
            ->andWhere('vlb.stRegistroAtivo = :stAtivo')
            ->setParameters([
                'publicacao' => $publicacao->getCoSeqPublicacao(),
                'perfil' => $perfil->getCoSeqPerfil(),
                'nuMes' => $nuMes,
                'nuAno' => $nuAno,
                'stAtivo' => 'S',
            ]);
        
        if ($valorBolsaPrograma) {
            $qb->andWhere('vlb.coSeqValorBolsaPrograma <> :valorBolsaPrograma')
                ->setParameter('valorBolsaPrograma', $valorBolsaPrograma->getCoSeqValorBolsaPrograma());
        }
        
        $result = $qb->getQuery()->getResult();
        
        if ($result) {
            throw new ValorBolsaProgramaExistsException();
        }
    }
    
    /**
     * 
     * @param Publicacao $publicacao
     * @param Perfil $perfil     
     * @return \App\Entity\ValorBolsaPrograma
     * @throws \UnexpectedValueException
     */
    public function getVigenteByPublicacaoAndPerfil(
        Publicacao $publicacao,
        Perfil $perfil
    ) {
        $result = $this->createQueryBuilder('vlb')
            ->where('vlb.publicacao = :publicacao')
            ->andWhere('vlb.perfil = :perfil')            
            ->andWhere('vlb.stPeriodoVigencia = :stVigente')
            ->andWhere('vlb.stRegistroAtivo = :stAtivo')            
            ->setMaxResults(1)
            ->setParameters([
                'publicacao' => $publicacao->getCoSeqPublicacao(),
                'perfil' => $perfil->getCoSeqPerfil(),                
                'stVigente' => 'S',
                'stAtivo' => 'S',
            ])->getQuery()->getOneOrNullResult();
        
        if (!$result) {
            throw new \UnexpectedValueException('Registro não existe.');
        }
        
        return $result;
    }
    
    /**
     * 
     * @param Publicacao $publicacao
     * @param Perfil $perfil
     * @return \App\Entity\ValorBolsaPrograma
     * @throws \UnexpectedValueException
     */
    public function getLastValorBolsaVigenteByPublicacaoAndPerfil(
        Publicacao $publicacao,
        Perfil $perfil
    ) {
        $result = $this->createQueryBuilder('vlb')
            ->where('vlb.publicacao = :publicacao')
            ->andWhere('vlb.perfil = :perfil')            
            ->andWhere('vlb.stPeriodoVigencia = :stVigente')
            ->andWhere('vlb.stRegistroAtivo = :stAtivo')            
            ->orderBy('vlb.nuAnoVigencia', 'DESC')
            ->addOrderBy('vlb.nuMesVigencia', 'DESC')
            ->setMaxResults(1)
            ->setParameters([
                'publicacao' => $publicacao->getCoSeqPublicacao(),
                'perfil' => $perfil->getCoSeqPerfil(),                
                'stVigente' => 'N',
                'stAtivo' => 'S',
            ])->getQuery()->getOneOrNullResult();
        
        if (!$result) {
            throw new \UnexpectedValueException('Registro não existe.');
        }
        
        return $result;
    }
    
    /**
     * 
     * @param Publicacao $publicacao
     * @param Perfil $perfil
     * @return []<\App\Entity\ValorBolsaPrograma>
     */
    public function findByPublicacaoAndPerfil(Publicacao $publicacao, Perfil $perfil)
    {
        return $this->createQueryBuilder('vlb')
            ->where('vlb.publicacao = :publicacao')
            ->andWhere('vlb.perfil = :perfil')
            ->andWhere('vlb.stRegistroAtivo = :stAtivo')
            ->setParameters([
                'publicacao' => $publicacao->getCoSeqPublicacao(),
                'perfil' => $perfil->getCoSeqPerfil(),
                'stAtivo' => 'S',
            ])->getQuery()->getResult();
    }
    
    /**
     * 
     * @param ParameterBag $pb
     * @return Doctrine\ORM\Query
     */
    public function findByFilter(ParameterBag $pb)
    {
        $now = new \DateTime();
        
        $qb = $this->createQueryBuilder('vlb')
            ->select('vlb, p, perf')
            ->innerJoin('vlb.publicacao', 'p')
            ->innerJoin('vlb.perfil', 'perf')
            ->innerJoin('p.programa', 'prog')
            ->where('vlb.stPeriodoVigencia = :stVigencia')
            ->andWhere('vlb.stRegistroAtivo = :stAtivo')
            ->setParameter('stVigencia', $pb->get('tipoConsulta') == 'V' ? 'S' : 'N', \PDO::PARAM_STR)
            ->setParameter('stAtivo', 'S', \PDO::PARAM_STR);
        
        if ($pb->get('tipoConsulta') <> 'V') {
            $criteria = ($pb->get('tipoConsulta') == 'H') ? '<' : '>';
            $qb->andWhere(
                $qb->expr()->orX(
                    'vlb.nuAnoVigencia '.$criteria.' :nuAno', 
                    $qb->expr()->andX(
                        'vlb.nuAnoVigencia = :nuAno',
                        'vlb.nuMesVigencia '.$criteria.' :nuMes')
                    )
                )->setParameter('nuMes', $now->format('m'), \PDO::PARAM_STR)
                ->setParameter('nuAno', $now->format('Y'), \PDO::PARAM_STR);
        }
        if ($pb->get('publicacao')) {
            $qb->andWhere('vlb.publicacao = :publicacao')
                ->setParameter('publicacao', $pb->get('publicacao')->getCoSeqPublicacao(),\PDO::PARAM_INT);
        }
        if ($pb->get('tipoParticipante')) {
            $qb->andWhere('vlb.perfil = :perfil')
                ->setParameter('perfil', $pb->get('tipoParticipante')->getCoSeqPerfil(), \PDO::PARAM_INT);
        }

        $this->orderPagination($qb, $pb);
        
        return $qb->getQuery();
    }
    
    /**
     * 
     * @return []<\App\Entity\ValorBolsaPrograma>
     */
    public function findPendentesToBeVigentes()
    {
        $now = new \DateTime();
        
        return $this->createQueryBuilder('vlb')
            ->where('vlb.nuAnoVigencia = :nuAno')
            ->andWhere('vlb.nuMesVigencia = :nuMes')
            ->andWhere('vlb.stRegistroAtivo = :stAtivo')
            ->andWhere('vlb.stPeriodoVigencia = :stVigencia')
            ->setParameters([
                'nuMes' => $now->format('m'),
                'nuAno' => $now->format('Y'),
                'stAtivo' => 'S',
                'stVigencia' => 'N',
            ])->getQuery()->getResult();
    }
}
