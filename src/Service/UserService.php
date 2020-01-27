<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Friendship;
use App\Exception\ValidationException;
use Doctrine\Common\Collections\Criteria;
use Respect\Validation\Validator as v;

class UserService extends Service
{
	public function getFriends($status = null)
	{
		$friends = $this->entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
		$friends = $friends->getFriends();

		if (!is_null($status)) {
			$criteria = Criteria::create();
			$criteria->Where(Criteria::expr()->eq('status', $status));

			$friends = $friends->matching($criteria);
		}

		$friends = array_map(function($friend){
		    return $friend->getUser()->jsonSerialize();
		}, $friends->toArray());

		return $friends;
	}

	public function updateStatus($friend_id, $status)
	{
		$friendship = $this->entityManager->getRepository(Friendship::class)->findOneBy([
			"user_id" 	=> $this->security->getUser()->getId(),
			"friend_id"	=> $friend_id,
		]);

		$friendship->setStatus($status);

		$this->entityManager->persist($friendship);
        $this->entityManager->flush();
	}

	public function addFriend($friend_id)
	{
		// Verify if not already send an invitation to the friend
		$friendship = $this->entityManager->getRepository(Friendship::class)->findOneBy([
			"user_id" 	=> $this->security->getUser()->getId(),
			"friend_id"	=> $friend_id,
		]);

		if (!empty($friendship))
			return;

		// Add the friend
		$friendship = new Friendship();

    	$friendship->setUserId($this->security->getUser()->getId())
    			   ->setFriendId($friend_id);

    	$this->entityManager->persist($friendship);
    	$this->entityManager->flush();
	}

	public function sendinvitation($emailTo)
	{
		v::email()->check($data['email']); //validate email

		$user = $this->entityManager->getRepository(User::class)->findOneBy(["email" => $emailTo]);

		if (!empty($user)) {
			$this->addFriend($user->getId());

			return;
		}

    	$userFrom = $this->security->getUser();

    	$message = (new \Swift_Message(sprintf("Invitation from %s", $userFrom->getName())))
	        ->setFrom($userFrom->getEmail())
	        ->setTo($emailTo)
	        ->setBody(
	            $this->renderView(
	                'emails/invite.html.twig',
	                [
	                	'referral_id' 	=> $userFrom->getId(),
	                	'emailTo'		=> $emailTo
	                ]
	            ),
	            'text/html'
	        )
	    ;

	    $this->mailer->send($message);
	}

	public function deleteInvitation($invitation_id)
	{
		$friendship = $this->entityManager->getRepository(Friendship::class)->find($invitation_id);

		if (empty($friendship))
			return;

		$this->entityManager->remove($friendship);
        $this->entityManager->flush();
	}

	public function save($data)
	{
		// Validations
		v::key('name')->check($data);
		v::key('email')->check($data);
		v::key('password')->check($data);
		v::key('city')->check($data);
		v::key('state')->check($data);

		v::NotBlank()->check($data['name']);
		v::NotBlank()->email()->check($data['email']);
		v::NotBlank()->check($data['password']);
		v::NotBlank()->check($data['city']);
		v::NotBlank()->check($data['state']);

		// Verify if email is not duplicated
		$user = $this->entityManager->getRepository(User::class)->findOneBy(["email" => $data["email"]]);

		if (!empty($user))
			throw new ValidationException("The email is already registered");

		// Persist data
		$user = new User();

		$user->setName($data['name'])
			 ->setEmail($data['email'])
			 ->setPassword($this->passwordEncoder->encodePassword($user, $data['password']))
			 ->setCity($data['city'])
			 ->setState($data['state']);

		if (v::key('bio')->validate($data))
			$user->setBio($data["bio"]);

		if (v::key('profile_picture')->validate($data))
			$user->setProfilePicture($data["profile_picture"]);

		$this->entityManager->persist($user);
        $this->entityManager->flush();

        // Get id of friend who invited by email and make he friend
        if (v::key('referral_id')->validate($data)) {
        	$friendship = new Friendship();

        	$friendship->setUserId($user->getId())
        			   ->setFriendId($referral_id);

        	$this->entityManager->persist($friendship);
        	$this->entityManager->flush();
        }
	}
}