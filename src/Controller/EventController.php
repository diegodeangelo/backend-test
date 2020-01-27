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

        $events = $this->eventService->search($data, $page);

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
    	$event = $this->eventService->show($event_id);

    	return $this->json($event);
    }

    /**
     * @Route("/event/{event_id}/edit")
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

    /**
     * @Route("/event/{event_id}/invitation")
     */
    public function updateStatusInvitation($event_id, Request $request)
    {
        $status = $request->get("status");

        $statusValue = [
            "reject"    => Friendship::STATUS_REJECTED,
            "accept"    => Friendship::STATUS_CONFIRMED,
        ];

        v::in(array_keys($statusValue))->setName('Status')->check($status); // validate status

        $this->eventService->updateInvitationsStatus($event_id, $status);

        return new Response();
    }

    /**
     * @Route("/event/{event_id}/invitation/send")
     */
    public function sendinvitation($event_id)
    {
        $users_id = json_decode($request->get("users_id"));

        $this->eventService->friendsInvitation($event_id, $users_id);

        return new Response();
    }
}
