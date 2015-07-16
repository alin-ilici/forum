<?php

namespace Forum\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setMethod('POST');

        $builder
            ->add(
                'username',
                'text',
                array(
                    'label' => 'Username',
                    'attr' => array(
                        'class' => ''
                    )
                )
            )
            ->add(
                'password',
                'text',
                array(
                    'label' => 'Password',
                    'attr' => array(
                        'class' => ''
                    )
                )
            )
            ->add(
                'firstName',
                'text',
                array(
                    'label' => 'First name',
                    'attr' => array(
                        'class' => ''
                    )
                )
            )
            ->add(
                'lastName',
                'text',
                array(
                    'label' => 'Last name',
                    'attr' => array(
                        'class' => ''
                    )
                )
            )
            ->add(
                'email',
                'text',
                array(
                    'label' => 'Email',
                    'attr' => array(
                        'class' => ''
                    )
                )
            )
            ->add(
                'save',
                'submit',
                array(
                    'label' => 'Register account'
                )
            );
    }

    public function getName()
    {
        return 'user';
    }
}