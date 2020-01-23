<?php

namespace App\Controller;

use App\Service\EventService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController
{
    private $eventService;

    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
    }

    /**
     * @Route("/event/all", name="event_index")
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
     * @Route("/event/show/{id}", name="event_show")
     */
    public function show($id)
    {
    	$events = json_encode($this->eventService->show($id));

    	return $this->json($events);
    }

    /**
     * @Route("/event/edit/{id}", name="event_new")
     */
    /*public function edit($id, Request $request, ValidatorInterface $validator, Security $security)
    {
    	$name = $request->get("name");
    	$email = $request->get("email");
    	$description = $request->get("description");
    	$date = $request->get("date");
    	$time = $request->get("time");
    	$place = $request->get("place");
    	$status = $request->get("status") ?? App\Entity\User::STATUS_ACTIVE;

    	if (isset($id)) {
        	$user = $entityManager->getRepository(Event::class)->find($id);

        	if (!$user) {
		        throw $this->createNotFoundException(
		            'No user found for id '.$id
		        );
		    }
        } else {
        	$user = new App\Entity\User();
        }

		$user->setName($name)
			 ->setEmail($email)
			 ->setDescription($description)
			 ->setDate($date)
			 ->setTime($time)
			 ->setPlace($place)
			 ->setUser($security->getUser())
			 ->setStatus($status);

		$errors = $validator->validate($user);

        if (count($errors) > 0) {
            return $this->json(["message" => $errors], 400);
        }
        
        $entityManager = $this->getDoctrine()->getRepository(Event::class);

        $entityManager->flush();
    }*/

    /**
     * @Route("/event/cancel/{id}", name="event_new")
     */
    /*public function cancel($id, Request $request)
    {
    	$request->request->set('status', Event::STATUS_CANCELLED);

    	$this->edit($id);
    }*/
}
