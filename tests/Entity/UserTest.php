<?php

namespace App\Tests;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
	protected $user;

	public function setUp()
	{
		$this->user = new User();
	}

	public function testSetAndGetName()
	{
		$this->user->setName("diego");

		$this->assertEquals("diego", $this->user->getName());
	}

	public function testSetAndGetEmail()
	{
		$this->user->setEmail("dideangelo@gmail.com");

		$this->assertEquals("dideangelo@gmail.com", $this->user->getEmail());
	}

	public function testSetAndGetPassword()
	{
		$this->user->setPassword("123456");

		$this->assertEquals("123456", $this->user->getPassword());
	}

	public function testSetAndGetBio()
	{
		$this->user->setBio("Meu nome é <b>Diego</b>");

		$this->assertEquals("Meu nome é Diego", $this->user->getBio());
	}

	public function testSetAndGetPictureProfile()
	{
		$this->user->setProfilePicture("https://teste.com/image/my_profile.jpg");

		$this->assertEquals("https://teste.com/image/my_profile.jpg", $this->user->getProfilePicture());
	}

	public function testSetAndGetCity()
	{
		$this->user->setCity("Nova Venécia");

		$this->assertEquals("Nova Venécia", $this->user->getCity());
	}

	public function testSetAndGetState()
	{
		$this->user->setState("Espírito Santo");

		$this->assertEquals("Espírito Santo", $this->user->getState());
	}
}