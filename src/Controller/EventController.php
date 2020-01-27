<?php

namespace App\Controller;

use App\Service\EventService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController
{
    private $eventService;

    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
    }

    /**
     * @Route("/events/")
     */
    public function index(Request $request)
    {
    	$page = $request->get("page", 1);
    	$dateStart = $request->get("dateStart");
    	$dateEnd = $request->get("dateEnd");
    	$place = $request->get("place");

        $data = [
            'dateStart'     => $dateStart,
            'dateEnd'       => $dateEnd,
            'place'         => $place
        ];

        $events = json_encode($this->eventService->search($data, $page));

		return $this->json($events);
    }

    /**
     * @Route("/event/registration")
     */
    public function registration(Request $request)
    {
        $this->eventService->save($request->request->all());

        return new Response();
    }

    /**
     * @Route("/event/{event_id}")
     */
    public function show($event_id)
    {
    	$events = json_encode($this->eventService->show($event_id));

    	return $this->json($events);
    }

    /**
     * @Route("/event/{event_id}/cancel")
     */
    public function edit($event_id)
    {
        $data = $request->request->all();
        $data['id'] = $event_id;

        $this->eventService->save($data);

        return new Response();
    }

    /**
     * @Route("/event/{event_id}/cancel")
     */
    public function cancel($event_id)
    {
        $this->eventService->save($event_id);

        return new Response();
    }
}
