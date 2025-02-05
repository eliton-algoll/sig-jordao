<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;
use AppBundle\Cpb\DicionarioCpb;

class CpbDefaultsValidator extends ConstraintValidator
{
    /**
     * 
     * @param string $value
     * @param Constraint $constraint
     * @throws UnexpectedTypeException
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof CpbDefaults) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\CbpDefaluts');
        }
        
        if (null !== DicionarioCpb::getDefault($constraint->campo) && $value !== DicionarioCpb::getDefault($constraint->campo)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ linha }}', $this->formatValue($constraint->linha))
                ->setParameter('{{ campo }}', $this->formatValue($constraint->campo))
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->addViolation();
        }
    }
}
