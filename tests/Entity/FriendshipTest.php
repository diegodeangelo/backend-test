<?php

namespace App\Tests;

use App\Entity\Friendship;
use PHPUnit\Framework\TestCase;

class FriendshipTest extends TestCase
{
	protected $friendship;

	public function setUp()
	{
		$this->friendship = new Friendship();
	}

	public function testSetAndGetStatus()
	{
		$this->friendship->setStatus(Friendship::STATUS_REJECTED);
		$this->assertEquals(0, $this->friendship->getStatus());

		$this->friendship->setStatus(Friendship::STATUS_CONFIRMED);
		$this->assertEquals(1, $this->friendship->getStatus());

		$this->friendship->setStatus(Friendship::STATUS_PENDING);
		$this->assertEquals(2, $this->friendship->getStatus());
	}
}