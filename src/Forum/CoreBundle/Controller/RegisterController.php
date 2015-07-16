<?php

namespace Forum\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class RegisterController extends Controller
{
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