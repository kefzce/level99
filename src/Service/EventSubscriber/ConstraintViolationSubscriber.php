<?php

namespace App\Service\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class ConstraintViolationSubscriber implements EventSubscriber
{
    public const VALIDATION_ERROR_MESSAGE = 'INPUT_DATA_ERRORS';

    public function getSubscribedEvents(): array
    {
        return [ValidationFailedException::class => 'onValidationException'];
    }

    public function onValidationException(ValidationFailedException $exception): JsonResponse
    {
        $errors = self::mapErrors($exception->getViolations());

        return new JsonResponse(['error' => self::VALIDATION_ERROR_MESSAGE, 'data' => $errors], Response::HTTP_BAD_REQUEST);
    }

    public static function mapErrors(ConstraintViolationListInterface $violationList): array
    {
        $errors = [];
        foreach ($violationList as $violation) {
            $errors[$violation->getPropertyPath()][] = $violation->getMessage();
        }

        return $errors;
    }
}
