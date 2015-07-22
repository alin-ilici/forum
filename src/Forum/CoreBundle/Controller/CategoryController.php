<?php

namespace Forum\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CategoryController extends Controller
{
    public function categoryAction($categorySlug)
    {
        /** @var \Forum\CoreBundle\Repository\CategoryRepository $categoryRepository */
        $categoryRepository = $this->getDoctrine()->getRepository("CoreBundle:Category");

        /** @var \Forum\CoreBundle\Entity\Category $category */
        $category = null;
        if ($categorySlug != null) {
            $category = $categoryRepository->findOneBy(array(
                'slug' => $categorySlug
            ));
        }

        /** @var \Forum\CoreBundle\Repository\TopicRepository $topicRepository */
        $topicRepository = $this->getDoctrine()->getRepository("CoreBundle:Topic");

        $subcategories = $category->getSubcategories();
        $lastTopic = null;
        foreach ($subcategories as $subcategory) {
            $lastTopic[$subcategory->getSlug()] = $topicRepository->findLatest(array($subcategory->getId()));
        }

        $whereAmI = '<a href="' . $this->generateUrl('forum_core_default_homepage') . '">Forum</a>';
        $whereAmI .= ' > ' . '<a href="' . $this->generateUrl('forum_core_default_homepage', array('forumSlug' => $category->getForum()->getSlug())) . '">' . $category->getForum()->getName() . '</a>';
        if ($categorySlug != null) {
            $whereAmI .= ' > ' . '<a href="' . $this->generateUrl('forum_core_category_category', array('categorySlug' => $categorySlug)) . '">' . $category->getName() . '</a>';
        }

        return $this->render('CoreBundle:Category:category.html.twig', array(
            'category' => $category,
            'whereAmI' => $whereAmI,
            'lastTopic' => $lastTopic
        ));
    }
}