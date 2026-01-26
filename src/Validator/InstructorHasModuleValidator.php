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

        if($value->getInterventionType()->getName() == "Autonomie")
        {
            return;
        } 

        $instructors = $value->getInstructors();

        $result = [];

        foreach($instructors as $instructor){
            $modules = $instructor->getModule();

            foreach($modules as $module){
                if($module->getName() === $value->getModule()->getName()){
                    $result[$instructor->getId()] = true;
                    break;
                } 
            }
        }


        if(count($instructors) !== count($result)){
        
            $this->context->buildViolation($constraint->message)
                ->atPath('instructors')
                ->addViolation();
        }
      
    }
}
