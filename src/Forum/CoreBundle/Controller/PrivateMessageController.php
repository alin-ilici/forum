<?php

namespace Forum\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class PrivateMessageController extends Controller
{
    public function deletePrivateMessageFileAction($privateMessageId) {
        if ($privateMessageId == null) {
            return new JsonResponse('fail');
        }

        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        /** @var \Forum\CoreBundle\Repository\PrivateMessageRepository $privateMessageRepository */
        $privateMessageRepository = $this->getDoctrine()->getRepository("CoreBundle:PrivateMessage");

        /** @var \Forum\CoreBundle\Entity\PrivateMessage $privateMessage */
        $privateMessage = $privateMessageRepository->findOneBy(array(
            'id' => $privateMessageId
        ));

        $privateMessage->removeFile();
        $em->persist($privateMessage);

        try {
            $em->flush();

            return new JsonResponse('success');
        } catch (\Exception $e) {
            return new JsonResponse('fail');
        }
    }
}