<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="Friendship")
 *
 * @Assert\Callback({"App\Utils\StatusValidator", "validate"})
 */
class Friendship
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
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $user_id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", inversedBy="friendships")
     */
    private $friend_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    public function __construct()
    {
        $this->friend_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): self
    {
        $this->user_id = $user_id;

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
