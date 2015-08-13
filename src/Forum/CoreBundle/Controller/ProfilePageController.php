<?php

namespace Forum\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ProfilePageController extends Controller
{
    protected $whereAmI;
    protected $topicsWithFirstMessage;

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

        $this->topicsWithFirstMessage = array();

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

        $this->whereAmI = '<a href="' . $this->generateUrl('forum_core_default_homepage') . '">Forum</a> > Viewing Profile: ' . $user->getUsername();

        return $this->render('CoreBundle:ProfilePage:profilePage.html.twig', array(
            'user' => $user,
            'whereAmI' => $this->whereAmI,
            'topicsWithFirstMessage' => $this->topicsWithFirstMessage
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
            'topicsWithFirstMessage' => $this->topicsWithFirstMessage
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
            'topicsWithFirstMessage' => $this->topicsWithFirstMessage
        ));
    }
}