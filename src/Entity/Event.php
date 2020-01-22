<?php

namespace App\Entity;

use App\Utils\Sanitize;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="Event")
 *
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 *
 * @Assert\Callback({"App\Utils\StatusValidator", "validate"})
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
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="user")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     *
     * @ORM\Column(type="integer")
     */
    private $status;

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

    public function getDate(): string
    {
        return $this->date;
    }

    public function setDate(string $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getTime(): string
    {
        return $this->time;
    }

    public function setTime(string $time): self
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

    public function getUser(): ?User
    {
        return $this->user_id;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

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
