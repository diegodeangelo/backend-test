<?php

namespace App\Service;

use App\Entity\Event;
use App\Entity\EventParticipants;
use App\Service\Service;
use Respect\Validation\Validator as v;
use App\Exception\ValidationException;

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
            throw new ValidationException("Event not found");

        return $event;
    }

    public function cancel($id)
    {
        $event = $this->entityManager->getRepository(Event::class)->find($id);
        
        if (empty($event))
            throw new ValidationException("Event not found");

        $event->setStatus(Event::STATUS_CANCELLED);

        $this->entityManager->persist($event);
        $this->entityManager->flush();
    }

    public function inviteFriends($event_id, $users_id)
    {
        // Validation
        v::NotBlank()->check($event_id);
        v::NotBlank()->check($users_id);

        $qb = $this->entityManager->getRepository(EventParticipants::class)->createQueryBuilder('e');

        $qb = $qb->select('e.participant_id')
                 ->andWhere('e.event_id = :event_id')
                 ->andWhere('e.participant_id IN (' . implode(",", $users_id) . ')')
                 ->setParameter('event_id', (int) $event_id)
                 ->getQuery();

        // Remove who was invited before
        $usersIdWithNoInvitation = array_diff($users_id, array_map(function($item){
            return $item["participant_id"];
        }, $qb->getResult()));

        $this->entityManager->getConnection()->getConfiguration()->setSQLLogger(null);

        $eventParticipants = new EventParticipants();

        foreach ($usersIdWithNoInvitation as $user_id) {
            $eventParticipants->setEventId($event_id)
                              ->setParticipantId($user_id);

            $this->entityManager->persist($eventParticipants);
        }
        $this->entityManager->flush();
    }

    public function updateInvitationStatus($event_id, $status)
    {
        $eventParticipants = $this->entityManager->getRepository(EventParticipants::class)->findOneBy([
            "participant_id"  => $this->security->getUser()->getId(),
            "event_id" => $event_id,
        ]);

        if (empty($eventParticipants))
            throw new ValidationException("You have not been invited to participate in this event");

        $event = $eventParticipants->getEvent();
        $eventDate = sprintf("%s %s", $event->getDate()->format('Y-m-d'), $event->getTime()->format("H:i:s"));
        
        v::date()->max($eventDate)->setName('"Date"')->check(date("Y-m-d H:i:s"));

        $eventParticipants->setStatus($status);

        $this->entityManager->persist($eventParticipants);
        $this->entityManager->flush();
    }

    public function save($data)
    {
        $isUpdate = v::key('id')->validate($data);

        if ($isUpdate) {
            $event = $this->entityManager->getRepository(Event::class)->find($data['id']);
        
            if (empty($event))
                throw new ValidationException("Event not found");
        }

        // Validations
        v::key('name')->check($data);
        v::key('description')->check($data);
        v::key('date')->check($data);
        v::key('time')->check($data);
        v::key('place')->check($data);

        v::NotBlank()->check($data['name']);
        v::NotBlank()->check($data['description']);
        v::NotBlank()->date()->check($data['date']);
        v::NotBlank()->sf('Time')->check($data['time']);
        v::NotBlank()->check($data['place']);

        $event = new Event();

        $event->setName($data['name'])
              ->setDescription($data['description'])
              ->setDate($data['date'])
              ->setTime($data['time'])
              ->setPlace($data['place']);

        if ($isUpdate)
            $event->setUserId($this->security->getUser()->getId());
        
        $this->entityManager->persist($event);
        $this->entityManager->flush();
    }
}