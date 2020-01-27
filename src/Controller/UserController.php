<?php

namespace App\Controller;

use App\Entity\Friendship;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Respect\Validation\Validator as v;

class UserController extends AbstractController
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

	 /**
     * @Route("/user/signup")
     */
    public function signup(Request $request)
    {
		$this->userService->save($request->request->all());

        return new Response();
	}

    /**
     * @Route("/user/friends/{status}", requirements={"status"="[a-zA-Z]+"})
     */
    public function viewFriends($status = null)
    {
        $statusValue = [
            "rejected"  => Friendship::STATUS_REJECTED,
            "confirmed" => Friendship::STATUS_CONFIRMED,
            "pending"   => Friendship::STATUS_PENDING,
        ];

        if (!is_null($status)) {
            v::in(array_keys($statusValue))->setName('User status')->check($status); // validate status
            
            $friends = $this->userService->getFriends($statusValue[$status]);

            return $this->json($friends);
        }

        $friends = $this->userService->getFriends();

        return $this->json($friends);
    }

    /**
     * @Route("/user/friend/{friend_id}/status", methods={"PUT"}, requirements={"friend_id"="\d+"})
     */
    public function updateStatusFriend($friend_id, Request $request)
    {
        $status = $request->get("status");

        $statusValue = [
            "reject"    => Friendship::STATUS_REJECTED,
            "accept"    => Friendship::STATUS_CONFIRMED,
        ];

        v::in(array_keys($statusValue))->setName('Status')->check($status); // validate status

        $this->userService->updateStatus($friend_id, $statusValue[$status]);

        return new Response();
    }

    /**
     * @Route("/user/invitation/{invitation_id}/delete", methods={"DELETE"}, requirements={"invitation_id"="\d+"})
     */
    public function deleteInvitation($invitation_id)
    {
        $this->userService->deleteInvitation($invitation_id);

        return new Response();
    }
}