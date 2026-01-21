<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
final class InterventionDuration extends Constraint
{
    public string $message = "L'intervention ne doit pas dépasser 4 heures !";

      public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
