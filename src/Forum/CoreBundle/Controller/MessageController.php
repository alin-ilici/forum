<?php

namespace Forum\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class MessageController extends Controller
{
    public function deleteMessageFileAction($messageId) {
        if ($messageId == null) {
            return new JsonResponse('fail');
        }

        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        /** @var \Forum\CoreBundle\Repository\MessageRepository $messageRepository */
        $messageRepository = $this->getDoctrine()->getRepository("CoreBundle:Message");

        /** @var \Forum\CoreBundle\Entity\Message $message */
        $message = $messageRepository->findOneBy(array(
            'id' => $messageId
        ));

        $message->removeFile();
        $em->persist($message);

        try {
            $em->flush();

            return new JsonResponse('success');
        } catch (\Exception $e) {
            return new JsonResponse('fail');
        }
    }
}