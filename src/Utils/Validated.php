<?php

namespace App\Utils;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class Validated
{
	public static function date(string $date): void
	{
		$dateConstraints = [
            new Assert\Date(),
            new Assert\NotBlank(),
        ];

        self::validate($date, $dateConstraints);
	}

	public static function numberPositive($number)
	{
		$positive = [
            new Assert\Positive(),
        ];

        self::validate($number, $positive);
	}

	public static function numberNonPositive($number)
	{
		$nonNegative = [
            new Assert\PositiveOrZero(),
        ];

        self::validate($number, $nonNegative);
	}

	public static function email($email)
	{
		$email = [
            new Assert\Email(),
        ];

        self::validate($number, $email);
	}

	public static function validate($value, $constraints)
	{
		$violations = Validation::createValidator()->validate($value, $constraints);

		self::catchExceptions($violations);
	}

	public static function entity($entity)
	{
		$violations = Validation::createValidator()->validate($entity);

		self::catchExceptions($violations);
	}

	public static function catchExceptions(?object $violations): void
	{
		if (empty($violations))
			return;
			
		foreach ($violations as $violation)
    		throw new BadRequestHttpException($violation->getMessage());
	}
}