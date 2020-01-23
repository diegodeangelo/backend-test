<?php

namespace App\Service;

use App\Entity\Event;
use App\Entity\User;
use App\Utils\Validated;

class EventService extends Service
{
	const EVENTS_PER_PAGE = 10;

	public function search($data, $page = 1)
	{
		$offset = ($page == 1) ? (0) : (2*($page-1)); // this calculate the offset of pagination

		$em = $this->entityManager;

		$qb = $em->getRepository(Event::class)->createQueryBuilder('e');

        Validated::numberPositive($page);

        if (!empty($date['dateStart']) && !empty($date['dateEnd'])) {
            // Validate dateStart
            Validated::date($data['dateStart']);

            $qb->andWhere('e.date >= :dateStart')
               ->setParameter('dateStart', $data['dateStart'] . ' 00:00:00');

            // Validate dateEnd
            Validated::date($data['dateEnd']);

            $qb->andWhere('event.date <= :dateEnd')
               ->setParameter('dateEnd', $data['dateEnd'] . ' 23:59:59');
        }

        // Validate place
        if (!empty($data['place'])) {
            $qb->andWhere('e.place LIKE :place')
               ->setParameter('place', '%' . $data['place'] . '%');
        }

        $qb = $qb->where('e.date BETWEEN :dateStart AND :dateEnd')
    		     ->where('e.date >= :dateStart')
    		     ->andWhere('e.date < :dateEnd')
                 ->orWhere('e.place LIKE :place')
                 ->setParameter('dateStart', $data['dateStart'] . " 00:00:00")
                 ->setParameter('dateEnd', $data['dateEnd'] . " 23:59:59")
                 ->setParameter('place', '%' . $data['place'] . '%')
                 ->setFirstResult($offset)
                 ->setMaxResults(self::EVENTS_PER_PAGE)
                 ->getQuery();

        $em->flush();

        return $qb->getArrayResult();
	}

    public function save($data)
    {
        $em = $this->entityManager;

        $user = $this->em->getRepository(User::class)->find($data['user_id']);
        
        if (!isset($user))
            throw new Exception("User not found", 1);

        $event = new Event();

        $event->setName($data['name'])
              ->setDescription($data['description'])
              ->setDate($data['date'])
              ->setTime($data['time'])
              ->setPlace($data['place'])
              ->setUserId($data['user_id'])
              ->setStatus($data['status']);
    }
}