<?php

// src/Validator/Constraints/DateRangeValidator.php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Form\FormInterface;

class DateRangeValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint \App\Validator\Constraints\DateRange */

        if (null === $value || '' === $value) {
            return;
        }

        // Get the root form data
        $form = $this->context->getObject();
        
        if (!$form instanceof FormInterface) {
            return;
        }

        $startDate = $form->get('start_Date')->getData();

        // Make sure both dates are available
        if ($startDate && $value) {
            $maxEndDate = (clone $startDate)->modify('+3 weeks');

            if ($value > $maxEndDate) {
                $this->context->buildViolation($constraint->message)
                    ->addViolation();
            }
        }
    }
}
