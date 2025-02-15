<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class SeiValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if ($value && !$this->validateSei($value)) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }

    /**
     * @param $nuSei
     * @return bool
     */
    private function validateSei($nuSei)
    {
        return (bool) preg_match('/\d{5}.\d{6}\/\d{4}-\d{2}/', $nuSei);
    }
}
