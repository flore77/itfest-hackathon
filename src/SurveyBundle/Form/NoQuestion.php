<?php

namespace SurveyBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NoQuestion extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add("name", "text", array("label" => "Name of the survey"))
            ->add("freeTextNo", "integer", array("label" => "Number of free text questions"))
            ->add("templateQNo", "integer", array("label" => "Number of template questions"))
            ->add("starQNo", "integer", array("label" => "Number of star questions"))
            ->add("Continue", "submit", array("attr" => array("class" => "btn-primary")));
    }

    public function getName() {
        return "noQuestion";
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            "data_class" => "SurveyBundle\Entity\Survey",
            'validation_groups' => array('step1')
        ));
    }
}