<?php

namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LogIn extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add("username", "text", array("label" => "Username or Email"))
            ->add("password", "password", array("label" => "Password"))
            ->add("Log in", "submit", array("attr" => array("class" => "btn-primary")));
    }

    public function getName() {
        return "logIn";
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            "data_class" => "UserBundle\Entity\User"
        ));
    }
}