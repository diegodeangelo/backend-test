<?php

namespace App\Controller;

use App\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Knp\Component\Pager\PaginatorInterface;

class EventController extends AbstractController
{
    const EVENTS_PER_PAGE = 10;

	private $repository;
	private $page;
    private $paginator;

    public function __construct(PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
    }

	private function getEventsBetween($dateStart, $dateEnd)
	{
		$events = $this->repository->findBetween($dateStart, $dateEnd);

		return $this->getPaginatedResults($events);
	}

	private function getEventsByRegion($place)
	{
		$events = $this->repository->findBy([
			"place" => $place
		]);

		return $this->getPaginatedResults($events);
	}

	private function getAllEvents()
	{
		return $this->getPaginatedResults($this->repository->findAll());
	}

	private function getPaginatedResults($events)
	{
		$events = $this->paginator->paginate($events, $this->page, self::EVENTS_PER_PAGE);

        return json_encode($events);
	}

    /**
     * @Route("/event/all", name="event_index")
     */
    public function index(Request $request)
    {
    	$this->page = $request->get("page", 1);
    	$dateStart = $request->get("dateStart");
    	$dateEnd = $request->get("dateEnd");
    	$place = $request->get("place");

    	$this->repository = $this->getDoctrine()->getRepository(Event::class);

    	if (isset($dateStart) && isset($dateEnd)) {
			return $this->getEventsBetween($dateStart, $dateEnd);
		}

		if (isset($place)) {
			return $this->getEventsByRegion($place);
		}

		return JsonResponse::fromJsonString($this->getAllEvents());
    }

    /**
     * @Route("/event/show/{id}", name="event_show")
     */
    public function show($id)
    {
    	$events = $this->getDoctrine()->getRepository(Event::class)->find($id);

    	return $this->json($events);
    }

    /**
     * @Route("/event/edit/{id}", name="event_new")
     */
    public function edit($id, Request $request, ValidatorInterface $validator, Security $security)
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
    }

    /**
     * @Route("/event/cancel/{id}", name="event_new")
     */
    public function cancel($id, Request $request)
    {
    	$request->request->set('status', Event::STATUS_CANCELLED);

    	$this->edit($id);
    }
}
