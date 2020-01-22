<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class InviteController extends AbstractController
{
	/**
     * @Route("/invite/all", name="invite_")
     */
    public function index(Request $request)
    {

    }
}