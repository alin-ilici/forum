<?php

namespace Forum\CoreBundle\Controller;

use Forum\CoreBundle\Entity\Conversation;
use Forum\CoreBundle\Entity\PrivateMessage;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ConversationController extends Controller
{
    public function sendNewPrivateMessageAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $fromUser = $this->get('security.token_storage')->getToken()->getUser();

        $toUser = $request->request->get('toUser');
        $conversationName = $request->request->get('conversationName');
        $privateMessageText = $request->request->get('privateMessageText');
        $file = $request->files->get('uploadedFilePM', null);

        /** @var \Forum\CoreBundle\Repository\UserRepository $userRepository */
        $userRepository = $this->getDoctrine()->getRepository("CoreBundle:User");

        /** @var \Forum\CoreBundle\Entity\User $toUser */
        $toUserEntity = $userRepository->findOneBy(array(
            'username' => $toUser
        ));

        /** @var \Forum\CoreBundle\Entity\Conversation $conversation */
        $conversation = new Conversation();
        $conversation->setFromUser($fromUser);
        $conversation->setToUser($toUserEntity);
        $conversation->setName($conversationName);
        $em->persist($conversation);

        /** @var \Forum\CoreBundle\Entity\PrivateMessage $privateMessage */
        $privateMessage = new PrivateMessage();
        $privateMessage->setUser($fromUser);
        $privateMessage->setConversation($conversation);
        $privateMessage->setName($privateMessageText);

        if ($file != null) {
            $privateMessage->setFile($file);
            $privateMessage->uploadFile();
        }

        $em->persist($privateMessage);

        try {
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Your private message was successfully sent!');
        } catch (\Exception $e) {
            $this->get('session')->getFlashBag()->add('fail', 'There was a problem sending your private message!');
        }

        return $this->redirect($this->generateUrl('forum_core_topic_topic', array('topicSlug' => '6-nam-pretium-tempor-ipsum-phasellus', 'page' => -1)));
    }
}