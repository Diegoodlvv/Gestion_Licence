<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class InCoursePeriod extends Constraint
{
    public string $message = 'L\'intervention doit se dérouler dans une semaine de cours éxistante.';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
