<?php

namespace Forum\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class SubcategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setMethod('POST');

        $builder
            ->add(
                'name',
                'text',
                array(
                    'label' => 'Subcategory name',
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
                    'label' => 'Subcategory description',
                    'required' => false,
                    'attr' => array(
                        'class' => 'form-control'
                    )
                )
            )
            ->add(
                'save',
                'submit',
                array(
                    'label' => 'Create',
                    'attr' => array(
                        'class' => 'btn btn-default'
                    )
                )
            );
    }

    public function getName()
    {
        return 'subcategory';
    }
}