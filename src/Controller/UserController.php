<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
	 /**
     * @Route("/registration", name="registration")
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder, ValidatorInterface $validator)
    {
    	$name = $request->get("name");
    	$email = $request->get("email");
    	$password = $request->get("password");
    	$city = $request->get("city");
    	$state = $request->get("state");

		$user = new App\Entity\User();

		$user->setName($name)
			 ->setEmail($email)
			 ->setPassword($encoder->encodePassword($user, $plainPassword))
			 ->setCity($city)
			 ->setState($state);

		$errors = $validator->validate($user);

        if (count($errors) > 0) {
            return $this->json(json_encode(["message" => $errors)), 400);
        }

        $entityManager = $this->getDoctrine()->getRepository(User::class);

        $entityManager->flush();
	}
}