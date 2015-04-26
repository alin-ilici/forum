<?php

namespace Forum\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CategoryController extends Controller
{
    public function categoryAction($categorySlug)
    {
        /** @var \Forum\CoreBundle\Repository\CategoryRepository $categoryRepository */
        $categoryRepository = $this->getDoctrine()->getRepository("CoreBundle:Category");

        $category = null;
        if ($categorySlug != null) {
            $category = $categoryRepository->findOneBy(array(
                'slug' => $categorySlug
            ));
        }

        return $this->render('CoreBundle:Category:category.html.twig', array(
            'category' => $category
        ));
    }
}