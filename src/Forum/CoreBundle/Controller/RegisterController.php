<?php

namespace Forum\CoreBundle\Controller;

use Forum\CoreBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Forum\CoreBundle\Form\UserType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class RegisterController extends Controller
{
    public function indexAction() {
        $form = $this->createForm(
            new UserType(),
            null,
            array(
                'action' => $this->generateUrl('forum_core_register_register_account')
            )
        );

        $whereAmI = '<a href="' . $this->generateUrl('forum_core_default_homepage') . '">Forum</a> > Registration page';

        return $this->render('CoreBundle:Register:registerPage.html.twig', array(
            'form' => $form->createView(),
            'whereAmI' => $whereAmI
        ));
    }

    public function registerAccountAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        /** @var \Forum\CoreBundle\Entity\User $user */
        $user = new User();

        $form = $this->createForm(
            new UserType(),
            $user,
            array(
                'action' => $this->generateUrl('forum_core_register_register_account')
            )
        );

        $form->handleRequest($request);

        if ($form->isValid()) {
            $user->uploadAvatar();

            $em->persist($user);

            try {
                $em->flush();
            } catch (\Exception $e) {
                $this->get('session')->getFlashBag()->add('fail', 'There was a problem registering your account!<br/>' . $e->getMessage());
                return $this->redirect($this->generateUrl('forum_core_register_index'));
            }
        }

        return $this->redirect($this->generateUrl('forum_core_default_index'));
    }

    public function checkForAction(Request $request) {
        $what = $request->request->get('what', null);
        $whatValue = $request->request->get('whatValue', null);
        $username = $request->request->get('username', null);

        $em = $this->getDoctrine()->getManager();

        /** @var \Forum\CoreBundle\Repository\UserRepository $userRepository */
        $userRepository = $this->getDoctrine()->getRepository("CoreBundle:User");

        /*$query = $userRepository->createQueryBuilder('u');
        $result = $query->where($query->expr()->like("u.$what", ':user'))
            ->setParameter('user',"%$whatValue%")
            ->getQuery()
            ->getResult();*/

        if ($username != null) {
            /** @var \Forum\CoreBundle\Entity\User $user */
            $user = $userRepository->findOneBy(array(
                'username' => $username
            ));

            if (!$user instanceof User) {
                throw new \Exception('User not found in database!');
            }

            if ($user->getPassword() == $whatValue) {
                return new JsonResponse('success');
            } else {
                return new JsonResponse('fail');
            }
        }

        $result = $userRepository->findBy(array(
            $what => $whatValue
        ));

        if ($result == null) {
            return new JsonResponse('success');
        } else {
            return new JsonResponse('fail');
        }
    }

    public function sendMailAction()
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Hello Email')
            ->setFrom('sendSymfony2Mail@example.com') //nu face nimic
            ->setTo('alin_ilici@yahoo.com')
            ->setBody(
                $this->renderView(
                    'CoreBundle:Register:registrationMail.html.twig',
                    array('name' => 'Alin Ilici')
                ),
                'text/html'
            )
            /*
             * If you also want to include a plaintext version of the message
            ->addPart(
                $this->renderView(
                    'Emails/registration.txt.twig',
                    array('name' => $name)
                ),
                'text/plain'
            )
            */
        ;
        $this->get('mailer')->send($message);

        return $this->redirect($this->generateUrl('forum_core_default_index'));
    }
}