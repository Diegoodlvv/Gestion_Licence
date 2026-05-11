<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
final class AutonomieHasInstructors extends Constraint
{
    public string $message = "Il ne peut pas y avoir d'enseignants lors de cours en autonomie !";

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
