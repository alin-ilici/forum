<?php

namespace Forum\CoreBundle\Controller;

use Forum\CoreBundle\Entity\Message;
use Forum\CoreBundle\Form\MessageType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class TopicController extends Controller
{
    public function topicAction($topicSlug)
    {
        /** @var \Forum\CoreBundle\Repository\TopicRepository $topicRepository */
        $topicRepository = $this->getDoctrine()->getRepository("CoreBundle:Topic");

        $topic = null;
        if ($topicSlug != null) {
            $topic = $topicRepository->findOneBy(array(
                'slug' => $topicSlug
            ));
        }

        $form = $this->createForm(
            new MessageType(),
            null,
            array(
                'action' => $this->generateUrl('forum_core_topic_post_message', array('topicSlug' => $topicSlug))
            )
        );

        return $this->render('CoreBundle:Topic:topic.html.twig', array(
            'topic' => $topic,
            'form' => $form->createView(),
        ));
    }

    public function postMessageAction(Request $request, $topicSlug)
    {
        $em = $this->getDoctrine()->getManager();

        $topic = $em->getRepository('CoreBundle:Topic')->findOneBy(
            array(
                'slug' => $topicSlug
            )
        );

        if ($topic === null) {
            throw $this->createNotFoundException('Topic was not found!');
        }

        $form = $this->createForm(
            new MessageType(),
            null,
            array(
                'action' => $this->generateUrl('forum_core_topic_post_message', array('topicSlug' => $topicSlug))
            )
        );

        $form->handleRequest($request);

        if ($form->isValid()) {
            $formParams = $request->request->get('message');

            $message = new Message();
            $message->setName($formParams['name']);
            $message->setTopic($topic);
            $message->setUser($this->getUser());

            $em->persist($message);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Your comment was submitted successfully!');
        } else {
            $this->get('session')->getFlashBag()->add('fail', 'There was a problem submitting your comment!');
        }

        return $this->redirect($this->generateUrl('forum_core_topic_topic', array('topicSlug' => $topicSlug)));
    }
}
