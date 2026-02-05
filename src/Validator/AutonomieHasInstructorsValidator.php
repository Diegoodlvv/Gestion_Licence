<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class AutonomieHasInstructorsValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        
        if (null === $value->getInterventionType() || null === $value->getInstructors()) {
            return;
        }

        if(count($value->getInstructors()) > 0 && $value->getInterventionType()->getName() == 'Autonomie'){

            $this->context->buildViolation($constraint->message)
                ->atPath('instructors')
                ->addViolation()
            ;
        }
    }
}
