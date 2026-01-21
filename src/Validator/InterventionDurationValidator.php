<?php

namespace App\Validator;

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class InterventionDurationValidator extends ConstraintValidator
{
    public function validate(mixed $entity, Constraint $constraint): void
    {
        if (!$entity->getStartDate() || !$entity->getEndDate()) {
            return;
        }

        $diffInSeconds = $entity->getEndDate()->getTimestamp()
            - $entity->getStartDate()->getTimestamp();

        $maxSeconds = $constraint->maxHours * 3600;

        if ($diffInSeconds > $maxSeconds) {
            $this->context
                ->buildViolation($constraint->message)
                ->atPath('start_date')
                ->setParameter('{{ hours }}', $constraint->maxHours)
                ->addViolation();
        }
    }
}

