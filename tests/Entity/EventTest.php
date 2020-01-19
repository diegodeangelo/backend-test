<?php

namespace App\Tests;

use App\Entity\Event;
use PHPUnit\Framework\TestCase;

class EventTest extends TestCase
{
	protected $event;

	public function setUp()
	{
		$this->event = new Event();
	}

	public function testSetAndGetName()
	{
		$this->event->setName("Evento Teste");

		$this->assertEquals("Evento Teste", $this->event->getName());
	}

	public function testSetAndGetDescription()
	{
		$this->event->setDescription("Este é um <b>evento</b>");

		$this->assertEquals("Este é um evento", $this->event->getDescription());
	}

	public function testSetAndGetDate()
	{
		$date = new \Datetime();
		$date->setDate(2020, 1, 15);

		$this->event->setDate($date->format("Y-m-d"));

		$this->assertEquals("2020-01-15", $this->event->getDate());
	}

	public function testSetAndGetTime()
	{
		$time = new \Datetime();
		$time->setTime(8, 30, 0);

		$this->event->setTime($time->format("H:i:s"));

		$this->assertEquals("08:30:00", $this->event->getTime());
	}

	public function testSetAndGetPlace()
	{
		$this->event->setPlace("Place Test");

		$this->assertEquals("Place Test", $this->event->getPlace());
	}

	public function testSetAndGetStatus()
	{
		$this->event->setStatus(Event::STATUS_CANCELLED);
		$this->assertEquals(0, $this->event->getStatus());

		$this->event->setStatus(Event::STATUS_ACTIVE);
		$this->assertEquals(1, $this->event->getStatus());
	}
}