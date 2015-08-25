<?php

namespace Forum\CoreBundle\Controller;

use Forum\CoreBundle\Entity\Subcategory;
use Forum\CoreBundle\Form\SubcategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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

        $subcategoryForm = $this->createForm(
            new SubcategoryType(),
            null,
            array(
                'action' => $this->generateUrl('forum_core_category_create_or_edit_subcategory', array('categorySlug' => $categorySlug))
            )
        );

        return $this->render('CoreBundle:Category:category.html.twig', array(
            'category' => $category,
            'lastTopic' => $lastTopic,
            'subcategoryForm' => $subcategoryForm->createView(),
            'whereAmI' => $whereAmI
        ));
    }

    public function createOrEditSubcategoryAction(Request $request, $categorySlug, $subcategorySlug) {
        $em = $this->getDoctrine()->getManager();

        /** @var \Forum\CoreBundle\Repository\CategoryRepository $categoryRepository */
        $categoryRepository = $this->getDoctrine()->getRepository("CoreBundle:Category");

        /** @var \Forum\CoreBundle\Repository\SubcategoryRepository $subcategoryRepository */
        $subcategoryRepository = $this->getDoctrine()->getRepository("CoreBundle:Subcategory");

        /** @var \Forum\CoreBundle\Entity\Category $category */
        $category = $categoryRepository->findOneBy(array(
            'slug' => $categorySlug
        ));

        /** @var \Forum\CoreBundle\Entity\Subcategory $subcategory */
        $subcategory = null;
        if ($subcategorySlug != null) {
            $subcategory = $subcategoryRepository->findOneBy(array(
                'slug' => $subcategorySlug
            ));
        }

        $form = $this->createForm(
            new SubcategoryType(),
            $subcategory,
            array(
                'action' => $this->generateUrl('forum_core_subcategory_create_or_edit_topic', array('categorySlug' => $categorySlug))
            )
        );

        $form->handleRequest($request);

        if ($form->isValid()) {
            if ($subcategorySlug == null) {
                $data = $form->getData();

                $subcategory = new Subcategory();
                $subcategory->setCategory($category);
                $subcategory->setName($data['name']);
                $subcategory->setDescription($data['description']);
            }

            $em->persist($subcategory);

            try {
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', 'The new subcategory was created!');
                return $this->redirect($this->generateUrl(
                    'forum_core_subcategory_subcategory',
                    array('subcategorySlug' => $subcategory->getSlug())
                ));
            } catch (\Exception $e) {
                $this->get('session')->getFlashBag()->add('fail', 'There was a problem creating the new subcategory!' . $e->getMessage());
            }
        }

        return $this->redirect($this->generateUrl(
            'forum_core_category_category',
            array('categorySlug' => $categorySlug)
        ));
    }
}