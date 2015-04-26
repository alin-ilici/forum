<?php

namespace Forum\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SubcategoryController extends Controller
{
    public function subcategoryAction($subcategorySlug)
    {
        /** @var \Forum\CoreBundle\Repository\SubcategoryRepository $subcategoryRepository */
        $subcategoryRepository = $this->getDoctrine()->getRepository("CoreBundle:Subcategory");

        $subcategory = null;
        if ($subcategorySlug != null) {
            $subcategory = $subcategoryRepository->findOneBy(array(
                'slug' => $subcategorySlug
            ));
        }

        return $this->render('CoreBundle:Subcategory:subcategory.html.twig', array(
            'subcategory' => $subcategory
        ));
    }
}
