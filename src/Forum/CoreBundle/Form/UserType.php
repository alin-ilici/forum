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
                        'class' => 'form-control',
                        'autocomplete' => 'off',
                        'aria-describedby' => 'inputUsernameVerStatus'
                    )
                )
            )
            ->add(
                'password',
                'password',
                array(
                    'label' => 'Password',
                    'attr' => array(
                        'class' => 'form-control',
                        'autocomplete' => 'off'
                    )
                )
            )
            ->add(
                'firstName',
                'text',
                array(
                    'label' => 'First name',
                    'attr' => array(
                        'class' => 'form-control'
                    )
                )
            )
            ->add(
                'lastName',
                'text',
                array(
                    'label' => 'Last name',
                    'attr' => array(
                        'class' => 'form-control'
                    )
                )
            )
            ->add(
                'email',
                'text',
                array(
                    'label' => 'Email',
                    'attr' => array(
                        'class' => 'form-control',
                        'aria-describedby' => 'inputEmailVerStatus'
                    )
                )
            )
            ->add(
                'avatar',
                'file',
                array(
                    'required' => false
                )
            )
            ->add(
                'save',
                'submit',
                array(
                    'label' => 'Create account',
                    'attr' => array(
                        'class' => 'btn btn-primary disabled'
                    )
                )
            );
    }

    public function getName()
    {
        return 'user';
    }
}