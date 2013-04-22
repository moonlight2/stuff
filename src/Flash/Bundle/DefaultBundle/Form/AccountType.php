<?php

namespace Flash\Bundle\DefaultBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AccountType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('username')
                ->add('email')
                ->add('password')
                ->add('about')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Flash\Bundle\DefaultBundle\Entity\Account',
            'csrf_protection' => false,
        ));
    }

    public function getName() {
        return 'account';
    }

}
