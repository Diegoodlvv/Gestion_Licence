<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class InstructorHasModule extends Constraint
{
    public string $message = 'Vous devez choisir un ou plusieurs intervenants du module choisi';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
