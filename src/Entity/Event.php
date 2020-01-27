<?php

namespace App\Entity;

use App\Utils\Sanitize;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="event")
 *
 * @Assert\Callback({"App\Utils\StatusValidator", "validate"})
 */
class Event implements \JsonSerializable
{
    /**
     * @var integer Event status cancelled
     */
    const STATUS_CANCELLED = 0;

    /**
     * @var integer Event status active
     */
    const STATUS_ACTIVE = 1;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string Name of event
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     *
     * @var string Description of event
     */
    private $description;

    /**
     * @ORM\Column(type="date")
     *
     * @var string A "Y-m-d" formatted value
     */
    private $date;

    /**
     * @ORM\Column(type="time")
     *
     * @var string A "H:i:s" formatted value
     */
    private $time;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $place;

    /**
     * @ORM\Column(type="integer")
     */
    private $user_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="events")
     */
    private $user;

     /**
     * @ORM\OneToMany(targetEntity="App\Entity\EventParticipants", mappedBy="event")
     */
    private $myEvents;

    public function __construct()
    {
        $this->myEvents = new ArrayCollection();

        $this->setStatus(self::STATUS_ACTIVE);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = Sanitize::string($name);

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = Sanitize::string($description);

        return $this;
    }

    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getTime(): \DateTimeInterface
    {
        return $this->time;
    }

    public function setTime(?\DateTimeInterface $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getPlace(): ?string
    {
        return $this->place;
    }

    public function setPlace(?string $place): self
    {
        $this->place = Sanitize::string($place);

        return $this;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function setUserId(?int $user_id) : self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): ?User
    {
        $this->user = $user;

        return $this;
    }

    public function jsonSerialize()
    {
        return array(
            'id'            => $this->getId(),
            'name'          => $this->getName(),
            'description'   => $this->getDescription(),
            'date'          => $this->getDate()->format('Y-m-d'),
            'time'          => $this->getTime()->format('H:i:s'),
            'place'         => $this->getPlace(),
            'user'          => $this->getUser()->getName(),
            'status'        => $this->getStatus()
        );
    }
}
