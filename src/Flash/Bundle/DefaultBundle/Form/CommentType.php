<?php

namespace Flash\Bundle\DefaultBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CommentType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('text')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Flash\Bundle\DefaultBundle\Entity\Comment\PhotoComment',
            'csrf_protection' => false,
        ));
    }

    public function getName() {
        return 'photo_comment';
    }

}
