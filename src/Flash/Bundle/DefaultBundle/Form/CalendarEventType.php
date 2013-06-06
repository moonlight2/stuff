<?php

namespace Flash\Bundle\DefaultBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CalendarEventType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('title')
                ->add('text')
                ->add('isShown')
                ->add('allDay')
        ;
        $builder->add('start', 'date', array(
            'widget' => 'single_text',
            'format' => 'yyyy-MM-dd HH:mm:ss'
        ));
        $builder->add('end', 'date', array(
            'widget' => 'single_text',
            'format' => "yyyy-MM-dd HH:mm:ss"
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Flash\Bundle\DefaultBundle\Entity\Calendar\CalendarEvent',
            'csrf_protection' => false,
        ));
    }

    public function getName() {
        return 'calendarEvent';
    }

}
