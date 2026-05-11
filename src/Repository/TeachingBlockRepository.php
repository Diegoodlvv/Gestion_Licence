<?php

namespace App\Repository;

use App\Entity\TeachingBlock;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TeachingBlock>
 */
class TeachingBlockRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TeachingBlock::class);
    }

    //    /**
    //     * @return TeachingBlock[] Returns an array of TeachingBlock objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?TeachingBlock
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function teachingBlockByFilters(?string $code, ?string $name): ?QueryBuilder
    {
        $qb = $this->createQueryBuilder('t');

        if ($code) {
            $qb->andWhere('LOWER(t.code) LIKE LOWER(:code)')
                ->setParameter('code', '%'.$code.'%');
        }

        if ($name) {
            $qb->andWhere('LOWER(t.name) LIKE LOWER(:name)')
                ->setParameter('name', '%'.$name.'%');
        }

        return $qb;
    }
}
