<?php

namespace Forum\CoreBundle\Controller;

use Forum\CoreBundle\Entity\Notification;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class NotificationController extends Controller
{
    public function checkForNewNotificationsAction($userId) {
        $em = $this->getDoctrine()->getManager();

        /** @var \Forum\CoreBundle\Repository\NotificationRepository $notificationRepository */
        $notificationRepository = $this->getDoctrine()->getRepository("CoreBundle:Notification");

        /** @var \Forum\CoreBundle\Entity\Notification[] $notifications */
        $notifications = $notificationRepository->findBy(array(
            'forIdUser' => $userId,
            'seen' => Notification::NOT_SEEN
        ));

        $results = array();
        foreach ($notifications as $notification) {
            $data = array();
            $data['type'] = $notification->getType();
            $data['extraInfo'] = $notification->getExtraInfo();

            $results[] = $data;
        }

        $em->getConnection()->close();

        return new JsonResponse($results);
    }

    public function updateNotificationsAction($forUserId) {
        if ($forUserId == null) {
            return;
        }

        $em = $this->getDoctrine()->getManager();

        /** @var \Forum\CoreBundle\Repository\NotificationRepository $notificationRepository */
        $notificationRepository = $this->getDoctrine()->getRepository("CoreBundle:Notification");

        /** @var \Forum\CoreBundle\Entity\Notification[] $notifications */
        $notifications = $notificationRepository->findBy(array(
            'forIdUser' => $forUserId
        ));

        foreach ($notifications as $notification) {
            $notification->setSeen(Notification::SEEN);
            $em->persist($notification);
        }

        try {
            $em->flush();
            $em->getConnection()->close();

            return new JsonResponse('success');
        } catch (\Exception $e) {
            return new JsonResponse('error');
        }
    }
}