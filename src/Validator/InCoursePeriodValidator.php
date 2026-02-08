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
            if($entity->getStartDate()->format('d-m-Y') >= $period->getStartDate()->format('d-m-Y') && $entity->getEndDate()->format('d-m-Y') <= $period->getEndDate()->format('d-m-Y')){
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