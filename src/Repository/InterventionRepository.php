<?php

namespace App\Repository;

use App\Entity\Intervention;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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

    public function findByFilters(?string $startDate, ?string $endDate, ?string $module): array
    {
        $qb = $this->createQueryBuilder('i');

        if (!empty($startDate) && !empty($endDate)) {

            $qb->andWhere('i.date >= :startDate')
                ->andWhere('i.date <= :endDate')
                ->setParameter('startDate', new \DateTime($startDate))
                ->setParameter('endDate', new \DateTime($endDate));
        } elseif (!empty($startDate)) {

            $qb->andWhere('i.date >= :startDate')
                ->setParameter('startDate', new \DateTime($startDate));
        } elseif (!empty($endDate)) {

            $qb->andWhere('i.date <= :endDate')
                ->setParameter('endDate', new \DateTime($endDate));
        }

        if (!empty($module)) {
            $qb->andWhere('i.module = :module')
                ->setParameter('module', $module);
        }

        return $qb->getQuery()->getResult();
    }
}
