<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class InCoursePeriod extends Constraint
{
    public string $message = 'L\'intervention doit se dérouler dans la période du cours.';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
