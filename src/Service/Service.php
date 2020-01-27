<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;

abstract class Service
{
	protected $entityManager;
	protected $passwordEncoder;
	protected $security;

	public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder, Security $security)
	{
		$this->entityManager = $em;
		$this->passwordEncoder = $passwordEncoder;
		$this->security = $security;
	}
}