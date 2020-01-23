<?php

namespace App\Service;

use App\Entity\Event;
use App\Entity\User;
use App\Service\Service;
use Respect\Validation\Validator as v;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EventService extends Service
{
	const EVENTS_PER_PAGE = 10;

	public function search($data, $page = 1)
	{
        // Validate page
        v::not(v::negative())->setName('"page"')->check($page);

		$offset = ($page == 1) ? (0) : (self::EVENTS_PER_PAGE*($page-1)); // this calculate the offset of pagination

		$qb = $this->entityManager->getRepository(Event::class)->createQueryBuilder('e');

        if (!empty($data['dateStart']) && !empty($data['dateEnd'])) {
            // Validate dateStart
            v::date()->setName('"dateStart"')->check($data['dateStart']);

            $qb->andWhere('e.date >= :dateStart')
               ->setParameter('dateStart', $data['dateStart']);

            // Validate dateEnd
            v::date()->setName('"dateEnd"')->check($data['dateEnd']);

            $qb->andWhere('event.date <= :dateEnd')
               ->setParameter('dateEnd', $data['dateEnd']);
        }

        // Validate place
        if (!empty($data['place'])) {
            $qb->andWhere('e.place LIKE :place')
               ->setParameter('place', '%' . $data['place'] . '%');
        }

        $qb = $qb->setFirstResult($offset)
                 ->setMaxResults(self::EVENTS_PER_PAGE)
                 ->getQuery();

        $this->entityManager->flush();

        return $qb->getArrayResult();
	}

    public function show($id)
    {
        $event = $this->entityManager->getRepository(Event::class)->find($id);

        if (!$event)
            throw new NotFoundHttpException("Event not found");

        return $event;
    }

    public function save($data)
    {
        if (empty($data['user_id']))
            throw new BadRequestHttpException("user_id is required");

        $user = $this->entityManager->getRepository(User::class)->find($data['user_id']);
        
        if (!$user)
            throw new NotFoundHttpException("user not found");

        $event = new Event();

        $event->setName($data['name'])
              ->setDescription($data['description'])
              ->setDate($data['date'])
              ->setTime($data['time'])
              ->setPlace($data['place'])
              ->setUserId($data['user_id'])
              ->setStatus($data['status']);

        Validated::entity($event);
        
        /*$this->entityManager->persist($event);
        $this->entityManager->flush();*/
    }
}