<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

abstract class Service
{
	protected $entityManager;

	public function __construct(EntityManagerInterface $em)
	{
		$this->entityManager = $em;
	}
}