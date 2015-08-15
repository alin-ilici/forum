<?php

namespace Forum\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class NotificationController extends Controller
{
    public function checkForNewNotificationsAction($userId) {
        /** @var \Forum\CoreBundle\Repository\NotificationRepository $notificationRepository */
        $notificationRepository = $this->getDoctrine()->getRepository("CoreBundle:Notification");

        while (true) {
            /** @var \Forum\CoreBundle\Entity\Notification[] $notifications */
            $notifications = $notificationRepository->findBy(array(
                'forIdUser' => $userId,
                'seen' => 0
            ));

            if ($notifications != null) {
                return new JsonResponse($notifications);
            } else {
                sleep(5);
            }
        }
    }
}