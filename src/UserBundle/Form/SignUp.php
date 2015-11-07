<?php

namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SignUp extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add("lastName", "text",  array("label" => "Last name"))
            ->add("firstName", "text",  array("label" => "First name"))
            ->add("username", "text",  array("label" => "Username"))
            ->add("password", "password",  array("label" => "Password"))
            ->add("verifyPassword", "password",  array("label" => "Password verification"))
            ->add("email", "email",  array("label" => "Email"))
            ->add("Sign up", "submit", array("attr" => array("class" => "btn-primary")));
    }

    public function getName() {
        return 'signUp';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            "data_class" => "UserBundle\Entity\User"
        ));
    }
}