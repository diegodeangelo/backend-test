<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class EventRepository extends EntityRepository
{
    public function findBetween($dateStart, $dateEnd)
    {
		$qb->select('e')
		   ->from('App\Entity\Event','e')
		   ->add('where', $qb->expr()->between(
		            'e.date',
		            ':from',
		            ':to'
		        )
		    )
		   ->setParameters(array('from' => $dateStart, 'to' => $dateEnd));

		return $qb->getResult();
    }
}