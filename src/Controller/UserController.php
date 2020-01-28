<?php

namespace App\Controller;

use App\Entity\Friendship;
use App\Entity\EventParticipants;
use App\Service\UserService;
use App\Service\EventService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Respect\Validation\Validator as v;

class UserController extends AbstractController
{
    private $userService;

    /**
     * @api {post} /api/login_check To authenticate user
     * @apiGroup User
     * @apiParam {String} username The username of User.
     * @apiParam {String} password The password of User.
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     * {
     *      "token": "[your-token]"
     * }
     * @apiErrorExample {json} Error-Response:
     *     HTTP/1.1 401 Invalid credentials
     * {
     *      "code": 401,
     *      "message": "Invalid credentials"
     * }
     */

    /**
     *
     */

    public function __construct(UserService $userService, EventService $eventService)
    {
        $this->userService = $userService;
        $this->eventService = $eventService;
    }
    /**
     * @api {post} /api/user/signup Register user
     * @apiGroup User
     * @apiParam {String} name The name of User.
     * @apiParam {String} email The email of User.
     * @apiParam {String} password The password of User.
     * @apiParam {String} bio The profile of User (optional).
     * @apiParam {String} profile_picture The profile picture of User (optional).
     * @apiParam {String} city The city of User.
     * @apiParam {String} state The name of User.
     * @apiParam {String} referral_id The ID of User who invitated (optional).
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     * @apiErrorExample {json} Error-Response:
     *     HTTP/1.1 400 Bad Request
     */

	 /**
     * @Route("/user/signup", methods={"POST"})
     */
    public function signup(Request $request)
    {
		$this->userService->save($request->request->all());

        return new Response();
	}

    /**
     * @api {get} /api/user/friends/:status Show friends by status
     * @apiGroup User
     * @apiParam {String} status The status of user: rejected, confirmed or pending.
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     * @apiErrorExample {json} Error-Response:
     *     HTTP/1.1 400 Bad Request
     */

    /**
     * @Route("/user/friends/{status}", methods={"GET"}, requirements={"status"="[a-zA-Z]+"})
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
     * @api {put} /api/user/friend/:friend_id/status Update friends by status
     * @apiGroup User
     * @apiParam {Integer} friend_id The ID of a friend.
     * @apiParam {String} status The status of user: reject or accept.
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     * @apiErrorExample {json} Error-Response:
     *     HTTP/1.1 400 Bad Request
     */

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
     * @api {get} /api/user/events/:status Get all events you participating by status
     * @apiGroup User
     * @apiParam {String} status The status of user: rejected, confirmed or pending.
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     * @apiErrorExample {json} Error-Response:
     *     HTTP/1.1 400 Bad Request
     */

    /**
     * @Route("/user/events/{status}", methods={"GET"}, requirements={"status"="[a-zA-Z]+"})
     */
    public function getEventsParticipating($status = null)
    {
        $statusValue = [
            "rejected"  => EventParticipants::STATUS_REJECTED,
            "confirmed" => EventParticipants::STATUS_CONFIRMED,
            "pending"   => EventParticipants::STATUS_PENDING,
        ];

        if (!is_null($status)) {
            v::in(array_keys($statusValue))->setName('Event status')->check($status); // validate status
            
            $participating = $this->eventService->getEventsParticipatingByStatus($statusValue[$status]);

            return $this->json($participating);
        }

        $participating = $this->eventService->getEventsParticipatingByStatus();

        return $this->json($participating);
    }

    /**
     * @api {get} /api/user/myevents/:status Get all events you participating by status
     * @apiGroup User
     * @apiParam {String} status The status of user: cancelled or active.
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     * @apiErrorExample {json} Error-Response:
     *     HTTP/1.1 400 Bad Request
     */

    /**
     * @Route("/user/myevents/{status}", methods={"GET"}, requirements={"status"="[a-zA-Z]+"})
     */
    public function getMyEvents($status = null)
    {
        $statusValue = [
            "cancelled" => Event::STATUS_CANCELLED,
            "active"    => Event::STATUS_ACTIVE,
        ];

        if (!is_null($status)) {
            v::in(array_keys($statusValue))->setName('Event status')->check($status); // validate status
            
            $events = $this->eventService->getMyEvents($statusValue[$status]);

            return $this->json($events);
        }

        $events = $this->eventService->getMyEvents();

        return $this->json($events);
    }

    /**
     * @api {get} /api/user/invitation/:invitation_id Delete an invitation by ID
     * @apiGroup User
     * @apiParam {Integer} invitation_id The ID of invitation
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     * @apiErrorExample {json} Error-Response:
     *     HTTP/1.1 400 Bad Request
     */

    /**
     * @Route("/user/invitation/{invitation_id}", methods={"DELETE"}, requirements={"invitation_id"="\d+"})
     */
    public function deleteInvitation($invitation_id)
    {
        $this->userService->deleteInvitation($invitation_id);

        return new Response();
    }
}