<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class InCoursePeriodValidator extends ConstraintValidator
{
    public function validate(mixed $entity, Constraint $constraint): void
    {
        $coursePeriod = $entity->getCoursePeriod();

        if (
            !$coursePeriod ||
            !$entity->getStartDate() ||
            !$entity->getEndDate()
        ) {
            return;
        }

        if (
            $entity->getStartDate() < $coursePeriod->getStartDate() ||
            $entity->getEndDate() > $coursePeriod->getEndDate()
        ) {
            $this->context
                ->buildViolation($constraint->message)
                ->atPath('course_period') 
                ->addViolation();
        }
    }
}