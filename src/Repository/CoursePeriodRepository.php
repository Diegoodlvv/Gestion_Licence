<?php

namespace App\Repository;

use App\Entity\CoursePeriod;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CoursePeriod>
 */
class CoursePeriodRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CoursePeriod::class);
    }

    public function queryCoursePeriod(?\DateTime $startDate, ?\DateTime $endDate)
    {
        $qb = $this->createQueryBuilder('c');

        $qb->andWhere('c.start_date <= :startDate')
         ->andWhere('c.end_date >= :endDate')
         ->setParameter('startDate', $startDate->format('Y-m-d'))
         ->setParameter('endDate', $endDate->format('Y-m-d'))
         ->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
