<?php

namespace Mondo\BookingBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Mondo\AppBundle\Translator\MyTranslator;

class CustomerType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array( 'required' => true, 'label' => MyTranslator::trans('booking', 'customer.properties.name')))
            ->add('email', 'text', array( 'required' => true, 'label' => MyTranslator::trans('booking', 'customer.properties.email')))
            ->add('phone', 'text', array( 'required' => true, 'label' => MyTranslator::trans('booking', 'customer.properties.phone')))
            ->add('gender', 'choice', array('choices'  => array('m' => 'Male', 'f' => 'Female'), 'required' => false,
                'label' => MyTranslator::trans('booking', 'customer.properties.gender')))
            ->add('childs')
            ->add('adults')
            ->add('seniors')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Mondo\BookingBundle\Entity\Customer'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'mondo_bookingbundle_customer';
    }
}
