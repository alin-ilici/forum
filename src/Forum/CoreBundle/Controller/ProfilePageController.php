<?php

namespace Forum\CoreBundle\Controller;

use Forum\CoreBundle\Entity\Notification;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ProfilePageController extends Controller
{
    public function profilePageAction($username, $section)
    {
        $maxMessagesPerPage = $this->container->getParameter('maxMessagesPerPage');

        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        /** @var \Forum\CoreBundle\Repository\UserRepository $userRepository */
        $userRepository = $this->getDoctrine()->getRepository("CoreBundle:User");

        /** @var \Forum\CoreBundle\Repository\TopicRepository $topicRepository */
        $topicRepository = $this->getDoctrine()->getRepository("CoreBundle:Topic");

        /** @var \Forum\CoreBundle\Repository\MessageRepository $messageRepository */
        $messageRepository = $this->getDoctrine()->getRepository("CoreBundle:Message");

        /** @var \Forum\CoreBundle\Repository\PrivateMessageRepository $privateMessageRepository */
        $privateMessageRepository = $this->getDoctrine()->getRepository("CoreBundle:PrivateMessage");

        /** @var \Forum\CoreBundle\Repository\NotificationRepository $notificationRepository */
        $notificationRepository = $this->getDoctrine()->getRepository("CoreBundle:Notification");

        /** @var \Forum\CoreBundle\Entity\User $user */
        $user = $userRepository->findOneBy(array(
            'username' => $username
        ));

        /** @var \Forum\CoreBundle\Entity\Topic[] $topics */
        $topics = $topicRepository->findBy(array(
            'user' => $user->getId()
        ));

        /** @var \Forum\CoreBundle\Entity\Message $message */
        $message = null;

        $topicsWithFirstMessage = array();

        foreach ($topics as $topic) {
            $data = array();
            $data['topic'] = $topic;

            $message = $messageRepository->findOneBy(array(
                'user' => $user->getId(),
                'topic' => $topic->getId()
            ));

            $data['message'] = $message;
            $topicsWithFirstMessage[] = $data;
        }

        /** @var \Forum\CoreBundle\Entity\Message[] $userMessages */
        /*$userMessages = $messageRepository->findBy(array(
            'user' => $user->getId()
        ));*/
        $userMessages = $messageRepository->createQueryBuilder('m')
            ->where('m.user = :id')
            ->setParameter('id', $user->getId())
            ->getQuery()
            ->getResult();

        $messages = array();

        foreach ($userMessages as $userMessage) {
            $query = 'SELECT m.id, @Rank:= @Rank + 1 AS rank
                FROM message m
                JOIN (SELECT @Rank:= 0) r
                WHERE m.id_topic = ' . $userMessage->getTopic()->getId() . '
                ORDER BY m.date_created ASC';

            $stmt = $em->getConnection()->prepare($query);
            $stmt->execute();
            $results = $stmt->fetchAll();

            $data = array();
            foreach ($results as $result) {
                $data[$result['id']] = $result['rank'];
            }

            $page = ((int)$data[$userMessage->getId()] % $maxMessagesPerPage == 0) ?
                (int)((int)$data[$userMessage->getId()] / $maxMessagesPerPage) : (int)((int)$data[$userMessage->getId()] / $maxMessagesPerPage + 1);

            $messages[$userMessage->getId()]['message'] = $userMessage;
            $messages[$userMessage->getId()]['page'] = $page;

        }

        /** @var \Forum\CoreBundle\Entity\Notification[] $notifications */
        $notifications = $notificationRepository->findBy(array(
            'forIdUser' => $user->getId()
        ));

        /** @var \Forum\CoreBundle\Entity\PrivateMessage[] $privateMessages */
        $privateMessages = $privateMessageRepository->createQueryBuilder('pm')
            ->where('pm.user = :id_user')
            ->andWhere('pm.file IS NOT NULL')
            ->setParameter('id_user', $user->getId())
            ->getQuery()
            ->getResult();

        $whereAmI = '<a href="' . $this->generateUrl('forum_core_default_homepage') . '">Forum</a> > Viewing Profile: ' . $user->getUsername();

        $class = array();
        $class['general'] = '';
        $class['topics'] = '';
        $class['messages'] = '';
        $class['files'] = '';
        $class['notifications'] = '';
        $class['settings'] = '';
        $class[$section] = 'active';

        return $this->render('CoreBundle:ProfilePage:profilePage.html.twig', array(
            'user' => $user,
            'whereAmI' => $whereAmI,
            'topicsWithFirstMessage' => $topicsWithFirstMessage,
            'messages' => $messages,
            'privateMessages' => $privateMessages,
            'notifications' => $notifications,
            'class' => $class
        ));
    }

    public function changeOrRemoveAvatarAction(Request $request, $username)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var \Forum\CoreBundle\Repository\UserRepository $userRepository */
        $userRepository = $this->getDoctrine()->getRepository("CoreBundle:User");

        /** @var \Forum\CoreBundle\Entity\User $user */
        $user = $userRepository->findOneBy(array(
            'username' => $username
        ));

        $oldAvatarName = $user->getAvatar();

        $user->setAvatar($request->files->get('avatar'));
        $user->uploadAvatar($oldAvatarName);

        $em->persist($user);
        $em->flush();

        if ($request->files->get('avatar') == null) {
            return new JsonResponse('success');
        }

        return $this->redirect($this->generateUrl('forum_core_profile_page_profile_page', array(
            'username' => $username
        )));
    }

    public function changePasswordAction(Request $request, $username)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var \Forum\CoreBundle\Repository\UserRepository $userRepository */
        $userRepository = $this->getDoctrine()->getRepository("CoreBundle:User");

        /** @var \Forum\CoreBundle\Entity\User $user */
        $user = $userRepository->findOneBy(array(
            'username' => $username
        ));

        $plainPassword = $request->request->get('newPassword');
        $encoder = $this->container->get('security.password_encoder');
        $encoded = $encoder->encodePassword($user, $plainPassword);

        $user->setPassword($encoded);

        $em->persist($user);
        $em->flush();

        return $this->redirect($this->generateUrl('forum_core_profile_page_profile_page', array(
            'username' => $username
        )));
    }
}