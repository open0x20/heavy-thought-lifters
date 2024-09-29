<?php

namespace App\Exception;

use Symfony\Component\Validator\ConstraintViolationListInterface;
use Throwable;

class ValidationException extends \Exception
{
    protected array $violations;

    /**
     * ValidationException constructor.
     * @param ConstraintViolationListInterface $violations
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(ConstraintViolationListInterface $violations, int $code = 400, Throwable $previous = null)
    {
        $vMapped = array();
        foreach ($violations as $violation) {
            $vMapped[] = 'Field \'' . $violation->getPropertyPath() . '\': ' . $violation->getmessage();
        }

        $this->violations = $vMapped;

        parent::__construct(implode("\n", $this->violations), $code, $previous);
    }

    /**
     * @return array
     */
    public function getViolations(): array
    {
        return $this->violations;
    }
}