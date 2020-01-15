<?php

namespace App\Entity;

use App\Utils\Sanitize;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 */
class Event
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
     * @Assert\NotBlank
     *
     * @ORM\Column(type="string", length=255)
     *
     * @var string Name of event
     */
    private $name;

    /**
     * @Assert\NotBlank
     *
     * @ORM\Column(type="text")
     *
     * @var string Description of event
     */
    private $description;

    /**
     * @Assert\NotBlank
     * @Assert\Date
     *
     * @ORM\Column(type="date")
     *
     * @var string A "Y-m-d" formatted value
     */
    private $date;

    /**
     * @Assert\NotBlank
     * @Assert\Time
     *
     * @ORM\Column(type="time")
     *
     * @var string A "H:i:s" formatted value
     */
    private $time;

    /**
     * @Assert\NotBlank
     *
     * @ORM\Column(type="string", length=255)
     */
    private $place;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="event", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user_id;

    /**
     * @Assert\Choice(choices=Author::getStatusOptions(), message="Choose a valid status.")
     *
     * @ORM\Column(type="integer")
     */
    private $status;

    public static function getStatusOptions()
    {
        $reflector = new \ReflectionClass(__CLASS__);

        $status_constants = array_filter($reflector->getConstants(), function($key) {
            $prefix = "STATUS_";

            return strpos($key, $prefix) !== false;
        }, ARRAY_FILTER_USE_KEY);

        return array_values($status_constants);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = Sanitize::string($name);

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = Sanitize::string($description);

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    public function setTime(\DateTimeInterface $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getPlace(): ?string
    {
        return $this->place;
    }

    public function setPlace(string $place): self
    {
        $this->place = Sanitize::string($place);

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(User $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
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
}
