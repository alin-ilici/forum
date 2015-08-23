<?php

namespace Forum\CoreBundle\Controller;

use Forum\CoreBundle\Entity\Conversation;
use Forum\CoreBundle\Entity\Notification;
use Forum\CoreBundle\Entity\PrivateMessage;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ConversationController extends Controller
{
    public function showConversationsAction($conversationSlug) {
        // this is Symfony2 new way to get the logged in user
        $loggedInUser = $this->getUser();

        /** @var \Forum\CoreBundle\Repository\ConversationRepository $conversationRepository */
        $conversationRepository = $this->getDoctrine()->getRepository("CoreBundle:Conversation");

        /** @var \Forum\CoreBundle\Repository\PrivateMessageRepository $privateMessageRepository */
        $privateMessageRepository = $this->getDoctrine()->getRepository("CoreBundle:PrivateMessage");

        if ($conversationSlug == null) {
            // Received conversations
            $query = $conversationRepository->createQueryBuilder('c')
                ->Where('c.toUser = :id_user')
                ->setParameter('id_user', $loggedInUser->getId())
                ->orderBy('c.dateUpdated', 'DESC');

            /** @var \Forum\CoreBundle\Entity\Conversation[] $results */
            $results = $query->getQuery()->getResult();

            $conversationsReceived = array();
            foreach ($results as $result) {
                $data = array();
                $data['conversation'] = $result;
                $data['lastPerson'] = $privateMessageRepository->findOneBy(
                    array('conversation' => $result->getId()),
                    array('dateUpdated' => 'DESC')
                );

                $conversationsReceived[] = $data;
            }

            // Sent conversations
            $query = $conversationRepository->createQueryBuilder('c')
                ->where('c.fromUser = :id_user')
                ->setParameter('id_user', $loggedInUser->getId())
                ->orderBy('c.dateUpdated', 'DESC');

            /** @var \Forum\CoreBundle\Entity\Conversation[] $results */
            $results = $query->getQuery()->getResult();

            $conversationsSent = array();
            foreach ($results as $result) {
                $data = array();
                $data['conversation'] = $result;
                $data['lastPerson'] = $privateMessageRepository->findOneBy(
                    array('conversation' => $result->getId()),
                    array('dateUpdated' => 'DESC')
                );

                $conversationsSent[] = $data;
            }

            $whereAmI = '<a href="' . $this->generateUrl('forum_core_default_homepage') . '">Forum</a>' . ' > My Conversations';

            return $this->render('CoreBundle:Conversation:allConversations.html.twig', array(
                'conversationsReceived' => $conversationsReceived,
                'conversationsSent' => $conversationsSent,
                'whereAmI' => $whereAmI
            ));
        } else {
            /** @var \Forum\CoreBundle\Entity\Conversation $conversation */
            $conversation = $conversationRepository->findOneBy(array(
                'slug' => $conversationSlug
            ));

            /** @var \Forum\CoreBundle\Entity\PrivateMessage $privateMessages */
            $privateMessages = $privateMessageRepository->findBy(
                array('conversation' => $conversation->getId()),
                array('dateCreated' => 'ASC')
            );

            $whereAmI = '<a href="' . $this->generateUrl('forum_core_default_homepage') . '">Forum</a>' . ' > <a href="' . $this->generateUrl('forum_core_conversation_show_conversations') . '">My Conversations</a> > ' . $conversation->getName();

            return $this->render('CoreBundle:Conversation:conversation.html.twig', array(
                'conversation' => $conversation,
                'privateMessages' => $privateMessages,
                'whereAmI' => $whereAmI
            ));
        }
    }

    public function sendNewPrivateMessageAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        // this is Symfony2 new way to get the logged in user
        $fromUser = $this->getUser();

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
        $conversation->setName(preg_replace( "/\r|\n/", " ", $conversationName));
        $em->persist($conversation);

        /** @var \Forum\CoreBundle\Entity\PrivateMessage $privateMessage */
        $privateMessage = new PrivateMessage();
        $privateMessage->setUser($fromUser);
        $privateMessage->setConversation($conversation);
        $privateMessage->setName(preg_replace( "/\r|\n/", " ", $privateMessageText));

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

        /** @var \Forum\CoreBundle\Entity\Notification $notification */
        $notification = new Notification();
        $notification->setSeen(Notification::NOT_SEEN);
        $notification->setType(Notification::NEW_PRIVATE_MESSAGE);
        $notification->setForIdUser($toUserEntity);

        $data = array();
        $data['fromUsername'] = $fromUser->getUsername();
        $data['conversationName'] = $conversation->getName();
        $data['conversationSlug'] = $conversation->getSlug();
        $data['privateMessageId'] = $privateMessage->getId();

        $notification->setExtraInfo($data);

        $em->persist($notification);
        $em->flush();

        return $this->redirect($this->generateUrl('forum_core_conversation_show_conversations'));
    }

    public function replyToConversationAction(Request $request, $conversationSlug) {
        $em = $this->getDoctrine()->getManager();

        // this is Symfony2 new way to get the logged in user
        $loggedInUser = $this->getUser();

        $privateMessageText = $request->request->get('privateMessageText');
        $file = $request->files->get('uploadedFilePM', null);

        /** @var \Forum\CoreBundle\Repository\ConversationRepository $conversationRepository */
        $conversationRepository = $this->getDoctrine()->getRepository("CoreBundle:Conversation");

        /** @var \Forum\CoreBundle\Entity\Conversation $conversation */
        $conversation = $conversationRepository->findOneBy(array(
            'slug' => $conversationSlug
        ));

        $conversation->setDateUpdated(new \DateTime());
        $em->persist($conversation);

        /** @var \Forum\CoreBundle\Entity\PrivateMessage $privateMessage */
        $privateMessage = new PrivateMessage();
        $privateMessage->setName(preg_replace( "/\r|\n/", " ", $privateMessageText));
        $privateMessage->setConversation($conversation);
        $privateMessage->setUser($loggedInUser);

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

        /** @var \Forum\CoreBundle\Entity\Notification $notification */
        $notification = new Notification();
        $notification->setSeen(Notification::NOT_SEEN);
        $notification->setType(Notification::PRIVATE_MESSAGE_RESPONSE);

        $fromUser = $conversation->getFromUser();
        $toUser = $conversation->getToUser();

        if ($loggedInUser->getId() == $fromUser->getId()) {
            $notification->setForIdUser($toUser);
        } else {
            $notification->setForIdUser($fromUser);
        }

        $data = array();
        $data['fromUsername'] = $loggedInUser->getUsername();
        $data['conversationName'] = $conversation->getName();
        $data['conversationSlug'] = $conversation->getSlug();
        $data['privateMessageId'] = $privateMessage->getId();

        $notification->setExtraInfo($data);

        $em->persist($notification);
        $em->flush();

        return $this->redirect($this->generateUrl(
            'forum_core_conversation_show_conversations',
            array('conversationSlug' => $conversationSlug)
        ));
    }
}