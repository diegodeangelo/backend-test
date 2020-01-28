<?php

namespace App\Controller;

use App\Entity\EventParticipants;
use App\Service\EventService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Respect\Validation\Validator as v;

class EventController extends AbstractController
{
    private $eventService;

    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
    }

    /**
     * @Route("/events/", methods={"GET"})
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
     * @Route("/event/registration", methods={"POST"})
     */
    public function registration(Request $request)
    {
        $this->eventService->save($request->request->all());

        return new Response();
    }

    /**
     * @Route("/event/{event_id}", methods={"GET"}, requirements={"event_id"="\d+"})
     */
    public function show($event_id)
    {
    	$event = $this->eventService->show($event_id);

    	return $this->json($event);
    }

    /**
     * @Route("/event/{event_id}", methods={"PUT"}, requirements={"event_id"="\d+"})
     */
    public function edit($event_id)
    {
        $data = $request->request->all();
        $data['id'] = $event_id;

        $this->eventService->save($data);

        return new Response();
    }

    /**
     * @Route("/event/{event_id}/cancel", methods={"DELETE"}, requirements={"event_id"="\d+"})
     */
    public function cancel($event_id)
    {
        $this->eventService->save($event_id);

        return new Response();
    }

    /**
     * @Route("/event/{event_id}/invitation", methods={"PUT"}, requirements={"event_id"="\d+"})
     */
    public function updateStatusInvitation($event_id, Request $request)
    {
        $status = $request->get("status");

        $statusValue = [
            "reject"    => EventParticipants::STATUS_REJECTED,
            "accept"    => EventParticipants::STATUS_CONFIRMED,
        ];

        v::in(array_keys($statusValue))->setName('Status')->check($status); // validate status

        $this->eventService->updateInvitationStatus($event_id, $statusValue[$status]);

        return new Response();
    }

    /**
     * @Route("/event/{event_id}/invitation/send", methods={"POST"}, requirements={"event_id"="\d+"})
     */
    public function sendInvitation($event_id)
    {
        $users_id = json_decode($request->get("users_id"));

        $this->eventService->inviteFriends($event_id, $users_id);

        return new Response();
    }

    /**
     * @Route("/event/{event_id}/invitation/send/toall", methods={"POST"}, requirements={"event_id"="\d+"})
     */
    public function sendInvitationToAll($event_id)
    {
        $this->eventService->inviteAllFriends($event_id);

        return new Response();
    }
}
