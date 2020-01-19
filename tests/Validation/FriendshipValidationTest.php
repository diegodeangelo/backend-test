<?php

namespace App\Tests\Validation;

use App\Entity\Friendship;
use App\Tests\Validation\ValidationTest;


class FriendshipValidationTest extends ValidationTest
{
	public function setEntity(): Friendship
	{
		$friendship = new Friendship();

		$friendship->setStatus(Friendship::STATUS_CONFIRMED);
		
		return $friendship;
	}
}