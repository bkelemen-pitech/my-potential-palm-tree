<?php

declare(strict_types=1);

namespace App\Services;

use App\Exception\RequestDataValidationException;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use function json_encode;

class ValidationService
{
    private ValidatorInterface $validator;
    private PropertyAccessorInterface $propertyAccessor;

    public function __construct(
        ValidatorInterface $validator,
        PropertyAccessorInterface $propertyAccessor
    ) {
        $this->validator = $validator;
        $this->propertyAccessor = $propertyAccessor;
    }

    public function validate($value): ConstraintViolationListInterface
    {
        $violations = $this->validator->validate($value);

        if ($violations->count() > 0) {
            $errors = [];

            foreach ($violations as $violation) {
                if ($violation->getPropertyPath()) {
                    $parts = explode('[', str_replace(']', '', $violation->getPropertyPath()));
                    $propertyPath = (strpos($violation->getPropertyPath(), '[') === false)
                        ? '[' . $violation->getPropertyPath() . ']'
                        : '[' . implode('][', $parts) . ']';

                    $this->propertyAccessor->setValue(
                        $errors,
                        $propertyPath,
                        $violation->getMessage()
                    );
                } else {
                    $this->propertyAccessor->setValue($errors, '[global]', $violation->getMessage());
                }
            }

            throw new RequestDataValidationException(json_encode($errors));
        }

        return $violations;
    }
}
