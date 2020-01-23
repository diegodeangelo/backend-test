<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="friendship")
 *
 * @Assert\Callback({"App\Utils\StatusValidator", "validate"})
 */
class Friendship
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $event_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $friend_id;

    public function __construct()
    {
        $this->friend = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEventId(): ?int
    {
        return $this->event_id;
    }

    public function setEventId(int $user_id): self
    {
        $this->event_id = $event_id;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getFriendId(): Collection
    {
        return $this->friend_id;
    }

    public function addFriendId(User $friendId): self
    {
        if (!$this->friend_id->contains($friendId)) {
            $this->friend_id[] = $friendId;
        }

        return $this;
    }

    public function removeFriendId(User $friendId): self
    {
        if ($this->friend_id->contains($friendId)) {
            $this->friend_id->removeElement($friendId);
        }

        return $this;
    }
}
