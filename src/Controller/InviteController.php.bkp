<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class InviteController extends AbstractController
{
	public $mailer;
	public $user;

	public function __construct(\Swift_Mailer $mailer, Security $security)
	{
		$this->mailer = $mailer;
		$this->user = $security->getUser();
	}

	/**
     * @Route("/invite", name="invite_index")
     */
    public function index(Request $request)
    {
    	$emailTo = $request->get("email");
    	$userFrom = $this->user;

    	$message = (new \Swift_Message(sprintf("Invitation from %s", $userFrom->getName()))
	        ->setFrom($userFrom->getEmail())
	        ->setTo($emailTo)
	        ->setBody(
	            $this->renderView(
	                'emails/invite.html.twig',
	                [
	                	'userFromId' 	=> $userFrom->getId(),
	                	'emailTo'		=> $emailTo
	                ]
	            ),
	            'text/html'
	        )
	    ;

	    $this->mailer->send($message);
    }
}