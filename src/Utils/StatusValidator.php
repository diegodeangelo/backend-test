<?php

namespace App\Utils;

use Symfony\Component\Validator\Context\ExecutionContextInterface;

class StatusValidator
{
    public static function validate($object, ExecutionContextInterface $context, $payload)
    {
        $reflector = new \ReflectionClass(get_class($object));

        $status_constants = array_filter($reflector->getConstants(), function($key) {
            $prefix = "STATUS_";

            return strpos($key, $prefix) !== false;
        }, ARRAY_FILTER_USE_KEY);

        if (!in_array($object->getStatus(), $status_constants)) {
        	$context->buildViolation('Invalid status')
                    ->atPath('status')
            		->addViolation();
        }
    }
}