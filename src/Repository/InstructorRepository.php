<?php

namespace App\Repository;

use App\Entity\Instructor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
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



    public function InstructorByFilters(?string $lastname, ?string $firstname, ?string $email): ?QueryBuilder
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

        return $qb;
    }

    public function InstructorInterventionsByFilters(int $instructorId, ?\DateTimeInterface $start_date, ?\DateTimeInterface $end_date, ?\App\Entity\Module $module): ?QueryBuilder
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

        return $qb;
    }
    public function getUsedHoursPerModuleForInstructor(int $instructorId): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $interventions = $qb->select('i, m')
            ->from('App\Entity\Intervention', 'i')
            ->join('i.module', 'm')
            ->join('i.instructors', 'inst')
            ->where('inst.id = :instructorId')
            ->setParameter('instructorId', $instructorId)
            ->getQuery()
            ->getResult();

        // SELECT i.*, m.* 
        // FROM intervention i
        // INNER JOIN module m ON i.module_id = m.id
        // INNER JOIN intervention_instructor ii ON i.id = ii.intervention_id
        // INNER JOIN instructor inst ON ii.instructor_id = inst.id
        // WHERE inst.id = :instructorId

        $usedHours = [];

        foreach ($interventions as $intervention) {
            $module = $intervention->getModule();
            if (!$module) {
                continue;
            }

            $moduleId = $module->getId();

            // Calcule la durée en heures
            $durationSeconds = $intervention->getEndDate()->getTimestamp() - $intervention->getStartDate()->getTimestamp();
            $durationHours = $durationSeconds / 3600;

            if (!isset($usedHours[$moduleId])) {
                $usedHours[$moduleId] = 0;
            }
            $usedHours[$moduleId] += $durationHours;
        }

        return $usedHours;
    }
}

// Resume de toute la fonction getUsedHoursPerModuleForInstructor en SQL uniquement
// SELECT 
//     m.id AS module_id, 
//     -- On calcule la différence entre fin et début (en secondes), on divise par 3600 pour avoir les heures, et on fait la SOMME
//     SUM(TIMESTAMPDIFF(SECOND, i.start_date, i.end_date) / 3600) AS total_heures_realisees
// FROM 
//     intervention i
// -- On joint la table module pour savoir à quel module l'intervention appartient
// JOIN 
//     module m ON i.module_id = m.id
// -- On joint la table de liaison entre intervention et instructor (car c'est une relation ManyToMany)
// JOIN 
//     intervention_instructor ii ON i.id = ii.intervention_id
// WHERE 
//     ii.instructor_id = :ton_instructor_id
// GROUP BY 
//     m.id; -- On regroupe les résultats par Module pour avoir une ligne par module