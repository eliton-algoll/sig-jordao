<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueValidator extends ConstraintValidator
{
    private $duplicates = array();
    
    public function validate($value, Constraint $constraint)
    {
        if (is_array($value) && $value && !$this->checkArrayIsUnique($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('%duplicates%', implode(', ', $this->duplicates))
                ->addViolation();
        }
    }
    
    public function checkArrayIsUnique($array) {
        
        $array2 = $array;
        
        foreach ($array as $key => $value) {
            if (is_array($value) && $value) {
                $this->checkArrayIsUnique($value);
            } else {
                foreach ($array2 as $key2 => $value2) {
                    if ($value == $value2 && $key != $key2) {
                        $this->duplicates[] = $value;
                    }
                }
            }
        }
        
        return true;
    }
}
