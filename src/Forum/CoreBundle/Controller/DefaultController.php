<?php

namespace Forum\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->redirect($this->generateUrl('forum_core_default_homepage'));
    }

    public function homepageAction($forumSlug)
    {
        /** @var \Forum\CoreBundle\Repository\ForumRepository $forumRepository */
        $forumRepository = $this->getDoctrine()->getRepository("CoreBundle:Forum");

        /** @var \Forum\CoreBundle\Repository\MessageRepository $messageRepository */
        $messageRepository = $this->getDoctrine()->getRepository("CoreBundle:Message");

        /** @var \Forum\CoreBundle\Entity\Forum[] $forums */
        if ($forumSlug == null) {
            $forums = $forumRepository->findAll();
        } else {
            $forums = $forumRepository->findBy(array(
                'slug' => $forumSlug
            ));
        }

        /** @var \Forum\CoreBundle\Repository\TopicRepository $topicRepository */
        $topicRepository = $this->getDoctrine()->getRepository("CoreBundle:Topic");

        $lastTopic = null;
        $lastMessagePersonForLastTopic = array();

        foreach ($forums as $forum) {
            $categories = $forum->getCategories();
            foreach ($categories as $category) {
                $subcategories = $category->getSubcategories();
                $subcategoriesIds = array();
                foreach ($subcategories as $subcategory) {
                    $subcategoriesIds[] = $subcategory->getId();
                }
                $lastTopic[$category->getSlug()] = $topicRepository->findLatest($subcategoriesIds);

                if ($lastTopic[$category->getSlug()] != null) {
                    $lastMessagePersonForLastTopic[$category->getSlug()] = $messageRepository->findBy(
                        array('topic' => $lastTopic[$category->getSlug()]->getId()),
                        array('dateUpdated' => 'DESC'),
                        1
                    );
                }
            }
        }

        $whereAmI = '<a href="' . $this->generateUrl('forum_core_default_homepage') . '">Forum</a>';
        if ($forumSlug != null) {
            $whereAmI .= ' > ' . '<a href="' . $this->generateUrl('forum_core_default_homepage', array('forumSlug' => $forumSlug)) . '">' . $forums[0]->getName() . '</a>';
        }

        return $this->render('CoreBundle:Default:index.html.twig', array(
            'forums' => $forums,
            'whereAmI' => $whereAmI,
            'lastTopic' => $lastTopic,
            'lastMessagePersonForLastTopic' => $lastMessagePersonForLastTopic
        ));
    }

    public function searchResultsAction(Request $request) {
        var_dump($request->request);
        die;
    }

    public function adminAction() {
        return new Response('Admin page!');
    }
}
