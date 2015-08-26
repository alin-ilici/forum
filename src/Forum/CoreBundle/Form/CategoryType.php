<?php

namespace Forum\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setMethod('POST');

        $builder
            ->add(
                'name',
                'text',
                array(
                    'label' => 'Category name',
                    'required' => true,
                    'attr' => array(
                        'class' => 'form-control'
                    )
                )
            )
            ->add(
                'description',
                'text',
                array(
                    'label' => 'Category description',
                    'required' => false,
                    'attr' => array(
                        'class' => 'form-control'
                    )
                )
            )
            ->add(
                'forum',
                'entity',
                array(
                    'class' => 'CoreBundle:Forum',
                    'choice_label' => 'name'
                )
            )
            ->add(
                'save',
                'submit',
                array(
                    'label' => 'Done',
                    'attr' => array(
                        'class' => 'btn btn-default'
                    )
                )
            );
    }

    public function getName()
    {
        return 'category';
    }
}