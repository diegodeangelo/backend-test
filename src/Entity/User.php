<?php

namespace App\Entity;

use App\Entity\Event;
use App\Entity\Friendship;
use App\Utils\Sanitize;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface; 

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User implements \JsonSerializable, UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $bio;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $profile_picture;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $state;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Event", mappedBy="user")
     */
    private $events;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Friendship", mappedBy="user")
     */
    private $myFriends;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\EventParticipants", mappedBy="participant")
     */
    private $eventParticipants;

    public function __construct()
    {
        $this->events = new ArrayCollection();
        $this->myFriends = new ArrayCollection();
        $this->event_participants = new ArrayCollection();
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {        
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(?string $bio): self
    {
        $this->bio = Sanitize::string($bio);

        return $this;
    }

    public function getProfilePicture(): ?string
    {
        return $this->profile_picture;
    }

    public function setProfilePicture(?string $profile_picture): self
    {
        $this->profile_picture = $profile_picture;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = Sanitize::string($city);

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): self
    {
        $this->state = Sanitize::string($state);

        return $this;
    }

    // Events
    public function getEvents(): Collection
    {
        return $this->events;
    }

    // Event participant
    public function getEventsParticipants(): Collection
    {
        return $this->eventParticipants;
    }

    // Friendships
    public function getFriends(): Collection
    {
        return $this->myFriends;
    }

    public function jsonSerialize()
    {
        return [
            'id'                => $this->getId(),
            'name'              => $this->getName(),
            'email'             => $this->getEmail(),
            'bio'               => $this->getBio(),
            'profile_picture'   => $this->getProfilePicture(),
            'city'              => $this->getCity(),
            'state'             => $this->getState(),
        ];
    }

    // Extra methods for UserInterface
    public function getUsername()
    {
        return $this->email;
    }

    public function getSalt()
    {
        return null;
    }

    public function getRoles()
    {
        return array('ROLE_USER');
    }

    public function eraseCredentials()
    {
    }
}
