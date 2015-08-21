<?php

namespace Forum\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class MessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setMethod('POST');

        $builder
            ->add(
                'name',
                'textarea',
                array(
                    'label' => 'Reply to this topic',
                    'attr' => array(
                        'class' => 'form-control makeSpaceBottom',
                        'rows' => 5
                    )
                )
            )
            ->add(
                'file',
                'file',
                array(
                    'label' => 'Upload file',
                    'required' => false,
                    'attr' => array(
                    )
                )
            )
            ->add(
                'save',
                'submit',
                array(
                    'label' => 'Post',
                    'attr' => array(
                        'class' => 'btn btn-primary btn-sm submitMessageButton rightPosition makeSpaceBottom'
                    )
                )
            );
    }

    public function getName()
    {
        return 'message';
    }
}