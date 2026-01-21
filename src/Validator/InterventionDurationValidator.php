<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class InterventionDurationValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        /* @var InterventionDuration $constraint */

        if (!$value->getStartDate() || !$value->getEndDate()) {
            return;
        }

       $duration = $value->getEndDate()->getTimestamp() - $value->getStartDate()->getTimestamp();

       $maxDuration = 4;
       
       if($maxDuration > $duration)
        {
             $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->addViolation();   
        }
    }
}
