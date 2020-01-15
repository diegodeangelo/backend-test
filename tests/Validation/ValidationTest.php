<?php

namespace App\Tests\Validation;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;

abstract class ValidationTest extends TestCase
{
	abstract public function setEntity(): object;

	public function testValidate()
	{
		$validator = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();
		$errors = $validator->validate($this->setEntity());

		$this->assertEquals(0, count($errors));
	}
}