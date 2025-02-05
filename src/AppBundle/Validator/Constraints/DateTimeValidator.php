<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraints\DateTimeValidator as DtV;
use Symfony\Component\Validator\Constraint;

class DateTimeValidator extends DtV
{
    /**
     * 
     * @param mixed $value
     * @param Constraint $constraint
     * @return null
     * @throws UnexpectedTypeException
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof DateTime) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\DateTime');
        }

        if (null === $value || '' === $value || $value instanceof \DateTimeInterface) {
            return;
        }

        if (!is_scalar($value) && !(is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }
        
        $originalValue = $value;
        $value = \DateTime::createFromFormat($constraint->format, $value);
        
        if (!$value instanceof \DateTimeInterface || $value->format($constraint->format) !== $originalValue) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode(DateTime::INVALID_DATE_ERROR)
                ->addViolation();
        }
    }
}
