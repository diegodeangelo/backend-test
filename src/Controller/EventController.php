<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController
{
    /**
     * @Route("/event/all", name="event_index")
     */
    public function index()
    {
        return $this->json(new \stdClass());
    }
}
