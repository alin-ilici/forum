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
            $plainPassword = $request->request->get('user')['password'];
            $encoder = $this->container->get('security.password_encoder');
            $encoded = $encoder->encodePassword($user, $plainPassword);

            $user->setPassword($encoded);
            $user->uploadAvatar();

            $em->persist($user);

            try {
                $data = array();
                $data['username'] = $request->request->get('user')['username'];
                $data['firstName'] = $request->request->get('user')['firstName'];
                $data['lastName'] = $request->request->get('user')['lastName'];
                $data['password'] = $plainPassword;
                $data['email'] = $request->request->get('user')['email'];
                $this->sendMail($data);

                $em->flush();
                $this->get('session')->getFlashBag()->add('success', 'Your new account was successfully created! You can now log in using it. An email was sent to `' . $user->getEmail() . '`!');
            } catch (\Exception $e) {
                $this->get('session')->getFlashBag()->add('fail', 'There was a problem registering your account!' . $e->getMessage());
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

            $encoder = $this->container->get('security.password_encoder');

            if ($encoder->isPasswordValid($user, $whatValue)) {
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

    /**
     * @param array $data
     */
    public function sendMail($data)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Welcome to `Forum AlinIlici`!')
            ->setFrom('forum.alinilici@gmail.com') //nu face nimic
            ->setTo($data['email'])
            ->setBody(
                $this->renderView(
                    'CoreBundle:Register:registrationMail.html.twig',
                    array('data' => $data)
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
    }
}