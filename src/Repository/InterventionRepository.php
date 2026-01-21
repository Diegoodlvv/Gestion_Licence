<?php

namespace App\Repository;

use App\Entity\Intervention;
use App\Entity\Module;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Intervention>
 */
class InterventionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Intervention::class);
    }

    public function queryFilters(?\DateTime $startDate, ?\DateTime $endDate, ?Module $module): QueryBuilder
    {
        $qb = $this->createQueryBuilder('i');

        if (!empty($startDate) && !empty($endDate)) {

            $qb->andWhere('i.start_date >= :startDate')
                ->andWhere('i.end_date <= :endDate')
                ->setParameter('startDate', $startDate)
                ->setParameter('endDate', $endDate);
        } elseif (!empty($startDate)) {

            $qb->andWhere('i.start_date >= :startDate')
                ->setParameter('startDate', $startDate);
        } elseif (!empty($endDate)) {

            $qb->andWhere('i.end_date <= :endDate')
                ->setParameter('endDate', $endDate);
        }

        if (!empty($module)) {
            $qb->andWhere('i.module = :module')
                ->setParameter('module', $module);
        }

        $qb->orderBy('i.id','desc');

        return $qb;
    }
}
