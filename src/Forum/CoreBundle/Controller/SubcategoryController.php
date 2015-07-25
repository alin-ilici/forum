<?php

namespace Forum\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SubcategoryController extends Controller
{
    /**
     * By default, the topics are filtered by "Recently updated"
     */
    public function subcategoryAction($subcategorySlug)
    {
        /** @var \Forum\CoreBundle\Repository\SubcategoryRepository $subcategoryRepository */
        $subcategoryRepository = $this->getDoctrine()->getRepository("CoreBundle:Subcategory");

        /** @var \Forum\CoreBundle\Repository\TopicRepository $topicRepository */
        $topicRepository = $this->getDoctrine()->getRepository("CoreBundle:Topic");

        /** @var \Forum\CoreBundle\Repository\MessageRepository $messageRepository */
        $messageRepository = $this->getDoctrine()->getRepository("CoreBundle:Message");

        /** @var \Forum\CoreBundle\Entity\Subcategory $subcategory */
        $subcategory = null;
        if ($subcategorySlug != null) {
            $subcategory = $subcategoryRepository->findOneBy(array(
                'slug' => $subcategorySlug
            ));
        }

        $topics = $topicRepository->findBy(
            array('subcategory' => $subcategory->getId()),
            array('dateUpdated' => 'DESC')
        );

        $lastMessage = null;
        foreach ($topics as $topic) {
            $lastMessage[$topic->getSlug()] = $messageRepository->findLatest($topic->getId());
        }

        $whereAmI = '<a href="' . $this->generateUrl('forum_core_default_homepage') . '">Forum</a>';
        $whereAmI .= ' > ' . '<a href="' . $this->generateUrl('forum_core_default_homepage', array('forumSlug' => $subcategory->getCategory()->getForum()->getSlug())) . '">' . $subcategory->getCategory()->getForum()->getName() . '</a>';
        $whereAmI .= ' > ' . '<a href="' . $this->generateUrl('forum_core_category_category', array('categorySlug' => $subcategory->getCategory()->getSlug())) . '">' . $subcategory->getCategory()->getName() . '</a>';
        if ($subcategorySlug != null) {
            $whereAmI .= ' > ' . '<a href="' . $this->generateUrl('forum_core_subcategory_subcategory', array('subcategorySlug' => $subcategorySlug)) . '">' . $subcategory->getName() . '</a>';
        }

        return $this->render('CoreBundle:Subcategory:subcategory.html.twig', array(
            'subcategory' => $subcategory,
            'topics' => $topics,
            'whereAmI' => $whereAmI,
            'lastMessage' => $lastMessage
        ));
    }
}
