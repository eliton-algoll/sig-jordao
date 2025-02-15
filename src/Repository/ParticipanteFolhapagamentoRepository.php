<?php
/**
 * Created by PhpStorm.
 * User: pauloe.oliveira
 * Date: 27/10/16
 * Time: 15:24
 */

namespace App\Repository;

use App\Entity\AgenciaBancaria;
use App\Repository\RepositoryAbstract;
use Doctrine\Persistence\ManagerRegistry;

class ParticipanteFolhapagamentoRepository extends RepositoryAbstract
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AgenciaBancaria::class);
    }
} 