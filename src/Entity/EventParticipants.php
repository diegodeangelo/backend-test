<?php

namespace App\Entity;

use App\Entity\Event;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="event_participants")
 *
 * @Assert\Callback({"App\Utils\StatusValidator", "validate"})
 */
class EventParticipants
{
    /**
     * @var integer Friendship status rejected
     */
    const STATUS_REJECTED = 0;

    /**
     * @var integer Friendship status confirmed
     */
    const STATUS_CONFIRMED = 1;

    /**
     * @var integer Friendship status pending
     */
    const STATUS_PENDING = 2;

    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     */
    private $event_id;

    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     */
    private $participant_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Event", inversedBy="myEvent")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id")
     */
    private $event;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="eventParticipants")
     * @ORM\JoinColumn(name="participant_id", referencedColumnName="id")
     */
    private $participant;

    public function __construct()
    {
        $this->setStatus(self::STATUS_PENDING); // pending is the default status)
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getEvent()
    {
        return $this->event;
    }

    public function setEvent(Event $event)
    {
        $this->event = $event;
        $this->event_id = $event->getId();

        return $this;
    }

    public function getParticipant()
    {
        return $this->participant;
    }

    public function setParticipant(User $user)
    {
        $this->participant = $user;
        $this->participant_id = $user->getId();

        return $this;
    }
}
