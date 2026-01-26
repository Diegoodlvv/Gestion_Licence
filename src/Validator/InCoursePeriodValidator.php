<?php

namespace App\Validator;

use App\Repository\CoursePeriodRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class InCoursePeriodValidator extends ConstraintValidator
{
    public function __construct(private CoursePeriodRepository $course_period_repository)
    {
        $this->course_period_repository;
    }

    public function validate(mixed $entity, Constraint $constraint): void
    {
        if (!$entity->getStartDate() ||!$entity->getEndDate()) 
        {
            return;
        }

        $periods = $this->course_period_repository->findAll();

        $result = false;

        foreach($periods as $period){
            if($entity->getStartDate()->format('d') >= $period->getStartDate()->format('d') && $entity->getEndDate()->format('d') <= $period->getEndDate()->format('d')){
                $result = true;
                
            }
        }

        if (!$result) 
        {
            $this->context
                ->buildViolation($constraint->message)
                ->atPath('start_date') 
                ->addViolation();
        }
    }
}