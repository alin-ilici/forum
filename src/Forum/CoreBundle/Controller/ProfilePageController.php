<?php

namespace Forum\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ProfilePageController extends Controller
{
    protected $whereAmI;
    protected $topicsWithFirstMessage;
    protected $messages;

    public function profilePageAction($username)
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

        $this->topicsWithFirstMessage = array();

        foreach ($topics as $topic) {
            $data = array();
            $data['topic'] = $topic;

            $message = $messageRepository->findOneBy(array(
                'user' => $user->getId(),
                'topic' => $topic->getId()
            ));

            $data['message'] = $message;
            $this->topicsWithFirstMessage[] = $data;
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

        $this->messages = array();

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

            $this->messages[$userMessage->getId()]['message'] = $userMessage;
            $this->messages[$userMessage->getId()]['page'] = $page;

        }

        $this->whereAmI = '<a href="' . $this->generateUrl('forum_core_default_homepage') . '">Forum</a> > Viewing Profile: ' . $user->getUsername();

        return $this->render('CoreBundle:ProfilePage:profilePage.html.twig', array(
            'user' => $user,
            'whereAmI' => $this->whereAmI,
            'topicsWithFirstMessage' => $this->topicsWithFirstMessage,
            'messages' => $this->messages
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

        return $this->render('CoreBundle:ProfilePage:profilePage.html.twig', array(
            'user' => $user,
            'whereAmI' => $this->whereAmI,
            'topicsWithFirstMessage' => $this->topicsWithFirstMessage,
            'messages' => $this->messages
        ));
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

        $user->setPassword($request->request->get('newPassword'));

        $em->persist($user);
        $em->flush();

        return $this->render('CoreBundle:ProfilePage:profilePage.html.twig', array(
            'user' => $user,
            'whereAmI' => $this->whereAmI,
            'topicsWithFirstMessage' => $this->topicsWithFirstMessage,
            'messages' => $this->messages
        ));
    }
}