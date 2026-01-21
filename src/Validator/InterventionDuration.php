<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class InterventionDuration extends Constraint
{
    public string $message = 'La durée de l\'intervention ne doit pas dépasser {{ hours }} heures.';
    public int $maxHours = 4;

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}

