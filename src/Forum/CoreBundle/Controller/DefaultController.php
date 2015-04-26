<?php

namespace Forum\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($forumSlug)
    {
        /** @var \Forum\CoreBundle\Repository\ForumRepository $forumRepository */
        $forumRepository = $this->getDoctrine()->getRepository("CoreBundle:Forum");

        if ($forumSlug == null) {
            $forums = $forumRepository->findAll();
        } else {
            $forums = $forumRepository->findBy(array(
                'slug' => $forumSlug
            ));
        }

        /** @var \Forum\CoreBundle\Repository\TopicRepository $topicRepository */
        $topicRepository = $this->getDoctrine()->getRepository("CoreBundle:Topic");

        foreach ($forums as $forum) {
            $categories = $forum->getCategories();
            foreach ($categories as $category) {
                $subcategories = $category->getSubcategories();
                $subcategoriesIds = array();
                foreach ($subcategories as $subcategory) {
                    $subcategoriesIds[] = $subcategory->getId();
                }
                $lastTopic[$category->getSlug()] = $topicRepository->findLatest($subcategoriesIds);
            }
        }

        return $this->render('CoreBundle:Default:index.html.twig', array(
            'forums' => $forums,
            'lastTopic' => $lastTopic
        ));
    }
}
