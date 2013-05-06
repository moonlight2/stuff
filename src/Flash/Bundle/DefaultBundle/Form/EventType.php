<?php

namespace Flash\Bundle\DefaultBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EventType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('name')
                ->add('description')
                ->add('date')
                ->add('city')
                ->add('country')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Flash\Bundle\DefaultBundle\Entity\Event',
            'csrf_protection' => false,
        ));
    }

    public function getName() {
        return 'event';
    }

}
