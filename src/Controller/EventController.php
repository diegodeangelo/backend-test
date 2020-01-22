<?php

namespace App\Controller;

use App\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Knp\Component\Pager\PaginatorInterface;

class EventController extends AbstractController
{
	private $repository;
	private $page;

	private function getEventsBetween($dateStart, $dateEnd)
	{
		$events = $this->repository->findBetween($dateStart, $dateEnd);

		return getPaginatedResults($events);
	}

	private function getEventsByRegion($place)
	{
		$events = $this->repository->findBy([
			"place" => $place
		]);

		return getPaginatedResults($events);
	}

	private function getAllEvents()
	{
		return getPaginatedResults($this->repository->findAll());
	}

	private function getPaginatedResults($events, PaginatorInterface $paginator)
	{
		$events = $paginator->paginate($events, $this->page, 10);

        return $this->json($events);
	}

    /**
     * @Route("/event/all", name="event_index")
     */
    public function index(Request $request)
    {
    	$this->page = $request->get("page") ?? 1;
    	$dateStart = $request->get("dateStart");
    	$dateEnd = $request->get("dateEnd");
    	$place = $request->get("place");

    	$this->repository = $this->getDoctrine()->getRepository(Event::class);

    	if (isset($dateStart)) && isset($dateEnd)) {
			return $this->getEventsBetween($dateStart, $dateEnd);
		}

		if (isset($place)) {
			return $this->getEventsByRegion($place);
		}

		return $this->getAllEvents();
    }

    /**
     * @Route("/event/{id}", name="event_show")
     */
    public function show($id)
    {
    	$events = $this->getDoctrine()->getRepository(Event::class)->find($id);

    	return $this->json($events);
    }

    /**
     * @Route("/event/new", name="event_new")
     */
    public function new(Request $request, ValidatorInterface $validator, Security $security )
    {
    	$name = $request->get("name");
    	$email = $request->get("email");
    	$description = $request->get("description");
    	$date = $request->get("date");
    	$time = $request->get("time");
    	$place = $request->get("place");

		$user = new App\Entity\User();

		$user->setName($name)
			 ->setEmail($email)
			 ->setDescription($description)
			 ->setDate($date)
			 ->setTime($time)
			 ->setPlace($place)
			 ->setUser($security->getUser())
			 ->setStatus(App\Entity\User::STATUS_ACTIVE);

		$errors = $validator->validate($user);

        if (count($errors) > 0) {
            return $this->json(json_encode(["message" => $errors)), 400);
        }

        $entityManager = $this->getDoctrine()->getRepository(Event::class);

        $entityManager->flush();
    }
}
