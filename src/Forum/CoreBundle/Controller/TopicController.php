<?php

namespace Forum\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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

        return $this->render('CoreBundle:Topic:topic.html.twig', array(
            'topic' => $topic
        ));
    }
}
