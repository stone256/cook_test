<?php

namespace Stone256\CookBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class cookingType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
           // ->add('date')
            ->add('file')
            ->add('recipes')
           // ->add('food')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Stone256\CookBundle\Entity\cooking'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'stone256_cookbundle_cooking';
    }
}
