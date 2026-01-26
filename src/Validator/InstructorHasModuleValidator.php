<?php

namespace App\Validator;

use App\Entity\Instructor;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class InstructorHasModuleValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$value->getInstructors() || !$value->getModule()) {
            return;
        }

        $instructors = $value->getInstructors();

        $result = false;

        foreach($instructors as $instructor){
            $modules = $instructor->getModule();

            foreach($modules as $module){
                if($module->getName() === $value->getModule()->getName()){
                    $result = true;
                } else{
                    $result = false;
                }
            }
        }

        if($value->getInterventionType()->getName() != "Autonomie")
        {
            if(!$result){
                $this->context->buildViolation($constraint->message)
                    ->atPath('instructors')
                    ->addViolation()
                ;
            }
        }
      
    }
}
