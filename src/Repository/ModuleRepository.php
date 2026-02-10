<?php

namespace App\Repository;

use App\Entity\Module;
use App\Entity\TeachingBlock;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Module>
 */
class ModuleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Module::class);
    }

    //    /**
    //     * @return Module[] Returns an array of Module objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Module
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    /**
     * Finds all top-level modules (those without a parent) grouped by their teaching block.
     *
     * @return array Array of teaching blocks with their top-level modules
     */
    public function findModulesGroupedByTeachingBlock(): array
    {
        return $this->createQueryBuilder('m')
            ->leftJoin('m.teaching_block', 'tb')
            ->leftJoin('m.childrens', 'children')
            ->where('m.parent IS NULL')
            ->orderBy('tb.code', 'ASC')
            ->addOrderBy('m.name', 'ASC')
            ->getQuery()
            ->getResult();

        // SELECT m.*
        // FROM module m
        // LEFT JOIN teaching_block tb ON m.teaching_block_id = tb.id
        // LEFT JOIN module children ON children.parent_id = m.id
        // WHERE m.parent_id IS NULL
        // ORDER BY tb.code ASC, m.name ASC;

        // recupere tous les modules sans parents (evite les doublons avec le findAll),
        // classés par bloc d'enseignement et par nom
    }

    public function getTeachingBlockbyParent(?TeachingBlock $teachingBlock, ?Module $module = null): QueryBuilder
    {
        $qb = $this->createQueryBuilder('m')
            ->orderBy('m.name', 'ASC');

        if ($teachingBlock) {
            $qb->where('m.teaching_block = :tb')
                ->setParameter('tb', $teachingBlock);
        } else {
            $qb->where('1 = 0');
        }

        if ($module && $module->getId()) {
            $qb->andWhere('m.id != :id')
                ->setParameter('id', $module->getId());
        }

        return $qb;
    }
}
