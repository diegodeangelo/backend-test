<?php

namespace App\Service;

use App\Entity\User;
use App\Utils\Validated;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserService extends Service
{
	public $encoder;

	public function __construct(UserPasswordEncoderInterface $encoder)
	{
		$this->encoder = $encoder;
	}

	public function save($data)
	{
		Validated::validate($data['email'], [
			new Assert\Email()
		]);
echo $this->encoder->encodePassword($user, '123456');
		return;

		try {
			$user = new App\Entity\User();

			$user->setName($data['name'])
				 ->setEmail($data['email'])
				 ->setPassword($this->encoder->encodePassword($user, $data['password']))
				 ->setCity($data['city'])
				 ->setState($data['state']);

			$this->entityManager->persist($event);
	        $this->entityManager->flush();
	    } catch (\Exception $e) {

	    }
	}
}