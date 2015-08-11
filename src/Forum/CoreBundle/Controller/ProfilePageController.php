<?php

namespace Forum\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProfilePageController extends Controller
{
    public function profilePageAction($username)
    {
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

//        echo "<pre>";
//        \Doctrine\Common\Util\Debug::dump($topicsWithFirstMessage, 3);
//        echo "</pre>";
//        die;

        $whereAmI = '<a href="' . $this->generateUrl('forum_core_default_homepage') . '">Forum</a> > Viewing Profile: ' . $user->getUsername();

        return $this->render('CoreBundle:ProfilePage:profilePage.html.twig', array(
            'user' => $user,
            'whereAmI' => $whereAmI,
            'topicsWithFirstMessage' => $topicsWithFirstMessage
        ));
    }
}