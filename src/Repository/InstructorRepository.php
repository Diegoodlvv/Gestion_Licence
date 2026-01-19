<?php

namespace App\Repository;

use App\Entity\Instructor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Instructor>
 */
class InstructorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Instructor::class);
    }

    //    /**
    //     * @return Instructor[] Returns an array of Instructor objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('i.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Instructor
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }


    public function findInstructorByFilters(?string $lastname, ?string $firstname, ?string $email): array
    {
        $qb = $this->createQueryBuilder('i')
            ->leftJoin('i.user', 'u')
            ->addSelect('u');

        if ($lastname) {
            $qb->andWhere('LOWER(u.lastname) LIKE LOWER(:lastname)') 
                ->setParameter('lastname', '%' . $lastname . '%');
        }

        if ($firstname) {
            $qb->andWhere('LOWER(u.firstname) LIKE LOWER(:firstname)') 
                ->setParameter('firstname', '%' . $firstname . '%');
        }

        if ($email) {
            $qb->andWhere('LOWER(u.email) LIKE LOWER(:email)')
                ->setParameter('email', '%' . $email . '%');
        }

        return $qb->orderBy('u.lastname', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findInstructorInterventionsByFilters(int $instructorId, ?\DateTimeInterface $start_date, ?\DateTimeInterface $end_date, ?\App\Entity\Module $module): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('i')
            ->from('App\Entity\Intervention', 'i')
            ->join('i.instructors', 'inst')
            ->where('inst.id = :instructorId')
            ->setParameter('instructorId', $instructorId);

        if ($start_date) {
            $qb->andWhere('i.start_date >= :start_date')
                ->setParameter('start_date', $start_date);
        }

        if ($end_date) {
            $qb->andWhere('i.end_date <= :end_date')
                ->setParameter('end_date', $end_date);
        }

        if ($module) {
            $qb->andWhere('i.module = :module')
                ->setParameter('module', $module);
        }

        return $qb->orderBy('i.start_date', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
