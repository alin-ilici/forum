<?php

namespace Forum\CoreBundle\Controller;

use Forum\CoreBundle\Entity\Category;
use Forum\CoreBundle\Form\CategoryType;
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

        $categoryForm = $this->createForm(
            new CategoryType(),
            null,
            array(
                'action' => $this->generateUrl('forum_core_default_create_or_edit_category', array('forumSlug' => $forumSlug))
            )
        );

        $whereAmI = '<a href="' . $this->generateUrl('forum_core_default_homepage') . '">Forum</a>';
        if ($forumSlug != null) {
            $whereAmI .= ' > ' . '<a href="' . $this->generateUrl('forum_core_default_homepage', array('forumSlug' => $forumSlug)) . '">' . $forums[0]->getName() . '</a>';
        }

        return $this->render('CoreBundle:Default:index.html.twig', array(
            'forums' => $forums,
            'whereAmI' => $whereAmI,
            'lastTopic' => $lastTopic,
            'lastMessagePersonForLastTopic' => $lastMessagePersonForLastTopic,
            'categoryForm' => $categoryForm->createView()
        ));
    }

    public function createOrEditCategoryAction(Request $request, $forumSlug, $categorySlug) {
        $em = $this->getDoctrine()->getManager();

        /** @var \Forum\CoreBundle\Repository\ForumRepository $forumRepository */
        $forumRepository = $this->getDoctrine()->getRepository("CoreBundle:Forum");

        /** @var \Forum\CoreBundle\Repository\CategoryRepository $categoryRepository */
        $categoryRepository = $this->getDoctrine()->getRepository("CoreBundle:Category");

        /** @var \Forum\CoreBundle\Entity\Forum $forum */
        $forum = $categoryRepository->findOneBy(array(
            'slug' => $forumSlug
        ));

        /** @var \Forum\CoreBundle\Entity\Category $category */
        $category = new Category();
        if ($categorySlug != null) {
            $category = $categoryRepository->findOneBy(array(
                'slug' => $categorySlug
            ));
        }

        $form = $this->createForm(
            new CategoryType(),
            $category,
            array(
                'action' => $this->generateUrl('forum_core_default_create_or_edit_category', array('forumSlug' => $forumSlug))
            )
        );

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($category);

            try {
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', 'The new category was successfully created/edited!');
                return $this->redirect($this->generateUrl(
                    'forum_core_category_category',
                    array('categorySlug' => $category->getSlug())
                ));
            } catch (\Exception $e) {
                $this->get('session')->getFlashBag()->add('fail', 'There was a problem creating/editing the new subcategory!' . $e->getMessage());
            }
        }

        return $this->redirect($this->generateUrl(
            'forum_core_default_homepage',
            array('forumSlug' => $forumSlug)
        ));
    }

    public function forgotPasswordAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $username = $request->request->get('forgotPasswordUsername', null);
        $email = $request->request->get('forgotPasswordEmail', null);

        if ($username != null) {
            /** @var \Forum\CoreBundle\Repository\UserRepository $userRepository */
            $userRepository = $this->getDoctrine()->getRepository("CoreBundle:User");

            /** @var \Forum\CoreBundle\Entity\User $user */
            $user = $userRepository->findOneBy(array(
                'username' => $username
            ));

            if ($user == null) {
                $this->get('session')->getFlashBag()->add('fail', 'This username does not exist in our database!');
            } else {
                $newPassword = $this->randomPassword();
                $encoder = $this->container->get('security.password_encoder');
                $encoded = $encoder->encodePassword($user, $newPassword);

                $user->setPassword($encoded);

                $em->persist($user);

                $data = array();
                $data['user'] = $user;
                $data['newPassword'] = $newPassword;

                $this->sendForgotPasswordMail($data);

                try {
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', "An email was sent to `{$user->getEmail()}` with the new password.");
                } catch (\Exception $e) {
                    $this->get('session')->getFlashBag()->add('fail', 'An error occurred!');
                }
            }
        } elseif ($email != null) {
            /** @var \Forum\CoreBundle\Repository\UserRepository $userRepository */
            $userRepository = $this->getDoctrine()->getRepository("CoreBundle:User");

            /** @var \Forum\CoreBundle\Entity\User $user */
            $user = $userRepository->findOneBy(array(
                'email' => $email
            ));

            if ($user == null) {
                $this->get('session')->getFlashBag()->add('fail', 'This email does not exist in our database!');
            } else {
                $newPassword = $this->randomPassword();
                $encoder = $this->container->get('security.password_encoder');
                $encoded = $encoder->encodePassword($user, $newPassword);

                $user->setPassword($encoded);

                $em->persist($user);

                $data = array();
                $data['user'] = $user;
                $data['newPassword'] = $newPassword;

                $this->sendForgotPasswordMail($data);

                try {
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', "An email was sent to `{$user->getEmail()}` with the new password.");
                } catch (\Exception $e) {
                    $this->get('session')->getFlashBag()->add('fail', 'An error occurred!');
                }
            }
        }

        return $this->redirect($this->generateUrl('forum_core_default_homepage'));
    }

    public function randomPassword()
    {
        $alphabet = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $pass = '';
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass = $pass . $alphabet[$n];
        }

        return $pass;
    }

    /**
     * @param array $data
     */
    public function sendForgotPasswordMail($data)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Recover password - Forum AlinIlici')
            ->setFrom('forum.alinilici@gmail.com')
            ->setTo($data['user']->getEmail())
            ->setBody(
                $this->renderView(
                    'CoreBundle:Security:forgotPasswordMail.html.twig',
                    array('data' => $data)
                ),
                'text/html'
            );

        $this->get('mailer')->send($message);
    }
}
