<?php

namespace Forum\CoreBundle\Controller;

use Forum\CoreBundle\Entity\Message;
use Forum\CoreBundle\Form\MessageType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TopicController extends Controller
{
    public function topicAction($topicSlug)
    {
        /** @var \Forum\CoreBundle\Repository\TopicRepository $topicRepository */
        $topicRepository = $this->getDoctrine()->getRepository("CoreBundle:Topic");

        /** @var \Forum\CoreBundle\Entity\Topic $topic */
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

        $whereAmI = '<a href="' . $this->generateUrl('forum_core_default_homepage') . '">Forum</a>';
        $whereAmI .= ' > ' . '<a href="' . $this->generateUrl('forum_core_default_homepage', array('forumSlug' => $topic->getSubcategory()->getCategory()->getForum()->getSlug())) . '">' . $topic->getSubcategory()->getCategory()->getForum()->getName() . '</a>';
        $whereAmI .= ' > ' . '<a href="' . $this->generateUrl('forum_core_category_category', array('categorySlug' => $topic->getSubcategory()->getCategory()->getSlug())) . '">' . $topic->getSubcategory()->getCategory()->getName() . '</a>';
        $whereAmI .= ' > ' . '<a href="' . $this->generateUrl('forum_core_subcategory_subcategory', array('subcategorySlug' => $topic->getSubcategory()->getSlug())) . '">' . $topic->getSubcategory()->getName() . '</a>';

        return $this->render('CoreBundle:Topic:topic.html.twig', array(
            'topic' => $topic,
            'whereAmI' => $whereAmI,
            'form' => $form->createView(),
        ));
    }

    public function postMessageAction(Request $request, $topicSlug, $messageId)
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

            $message = null;
            if ($messageId !== null) {
                $message = $em->getRepository('CoreBundle:Message')->findOneBy(
                    array(
                        'id' => $messageId
                    )
                );
            }

            if ($message === null) {
                $message = new Message();
                $message->setName(preg_replace( "/\r|\n/", " ", $formParams['name']));
                $message->setTopic($topic);
                $message->setUser($this->getUser());
            } else {
                $message->setName(preg_replace( "/\r|\n/", " ", $formParams['name']));
            }

            $em->persist($message);

            $topic = $message->getTopic()->setDateUpdated($message->getDateUpdated());
            $em->persist($topic);

            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Your comment was successfully submitted/updated!');
        } else {
            $this->get('session')->getFlashBag()->add('fail', 'There was a problem submitting/updating your comment!');
        }

        return $this->redirect($this->generateUrl('forum_core_topic_topic', array('topicSlug' => $topicSlug)));
    }

    public function deleteMessageAction($messageId)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var \Forum\CoreBundle\Entity\Message $message */
        $message = $em->getRepository('CoreBundle:Message')->findOneBy(
            array(
                'id' => $messageId
            )
        );

        if (!empty($message)) {
            $em->remove($message);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'The message was successfully deleted!');

            return new JsonResponse('success');
        } else {
            $this->get('session')->getFlashBag()->add('fail', 'There was a problem deleting your message!');

            return new JsonResponse('fail');
        }
    }
}
