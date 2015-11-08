<?php

namespace SurveyBundle\Controller;

use SurveyBundle\Entity\FreeText;
use SurveyBundle\Entity\StarQ;
use SurveyBundle\Entity\Survey;
use SurveyBundle\Entity\TemplateQ;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SurveyBundle\Form\NoQuestion;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\NotBlank;

class SurveyController extends Controller {

    public function createSurveyStep1Action() {
        $repository = $this->getDoctrine()->getRepository("SurveyBundle:Survey");

        $survey = $repository->findOneBy(array("owner" => $this->getUser(), "status" => array(STEP1, STEP2, STEP3)));

        if ($survey !== null) {
            if ($survey->getStatus() === STEP2) {
                return $this->redirect($this->generateUrl('survey_creation_step2'));
            }
            else if ($survey->getStatus() === STEP3) {
                return $this->redirect($this->generateUrl('survey_creation_step3'));
            }
        }
        else {
            $survey = new Survey();
        }

        $form = $this->createForm(new NoQuestion(), $survey, array(
            "action" => $this->generateUrl("survey_creation_step1_check"),
            "method" => "POST"
        ));

        return $this->render("SurveyBundle:Default:surveyCreationStep1.html.twig", array("form" => $form->createView()));
    }

    public function createSurveyStep1CheckAction(Request $request) {
        $form = $this->createForm(new NoQuestion(), new Survey());

        $form->handleRequest($request);

        if ($form->isValid()) {
            $survey = $form->getData();
            $count = 0;

            if (!is_numeric($survey->getStarQNo())) {
                $survey->setStarQNo(0);
            }

            if (!is_numeric($survey->getTemplateQNo())) {
                $survey->setTemplateQNo(0);
            }

            if (!is_numeric($survey->getFreeTextNo())) {
                $survey->setFreeTextNo(0);
            }

            $count += $survey->getFreeTextNo() + $survey->getTemplateQNo() + $survey->getStarQNo();

            if ($count != 0) {
                $survey->setOwner($this->getUser());
                $survey->setStatus(STEP2);

                $em = $this->getDoctrine()->getEntityManager();

                for ($i = 0; $i < $survey->getFreeTextNo(); $i++) {
                    $q = new FreeText($survey);
                    $em->persist($q);

                    $survey->addFreeText($q);
                }

                for ($i = 0; $i < $survey->getTemplateQNo(); $i++) {
                    $q = new TemplateQ($survey);
                    $em->persist($q);

                    $survey->addTemplateQ($q);
                }

                for ($i = 0; $i < $survey->getStarQNo(); $i++) {
                    $q = new StarQ($survey);
                    $em->persist($q);

                    $survey->addStarQ($q);
                }

                $em->persist($survey);

                $em->flush();

                return $this->redirect($this->generateUrl('survey_creation_step2'));
            }
            else {
                $request->getSession()->getFlashBag()->add('error', "You have not selected any question type!");
            }
        }

        return $this->render("SurveyBundle:Default:surveyCreationStep1.html.twig", array("form" => $form->createView()));
    }

    public function createSurveyStep2Action() {
        $repository = $this->getDoctrine()->getRepository("SurveyBundle:Survey");

        $survey = $repository->findOneBy(array("owner" => $this->getUser(), "status" => STEP2));

        if ($survey === null) {
            return $this->redirect($this->generateUrl('survey_creation_step1'));
        }

        $form = $this->buildVoidForm($survey);

        $form->add("Continue", "submit", array("attr" => array("class" => "btn btn-primary")))
            ->setMethod("post")
            ->setAction($this->generateUrl("survey_creation_step2-check"));

        $form = $form->getForm();

        return $this->render("SurveyBundle:Default:surveyCreationStep2.html.twig", array("form" => $form->createView()));
    }

    public function createSurveyStep2CheckAction(Request $request) {
        $repository = $this->getDoctrine()->getRepository("SurveyBundle:Survey");

        $survey = $repository->findOneBy(array("owner" => $this->getUser(), "status" => STEP2));

        $form = $this->buildVoidForm($survey);

        $form->add("Continue", "submit", array("attr" => array("class" => "btn btn-primary")))
            ->setMethod("post")
            ->setAction($this->generateUrl("survey_creation_step2-check"));

        $form = $form->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $freeText = $survey->getFreeText();

            // Get the freeText questions.
            for ($i = 0; $i < $survey->getFreeTextNo(); $i++) {
                $freeText[$i]->setText($form->get($i + 1 . "free")->getData());
                $this->getDoctrine()->getEntityManager()->flush($freeText[$i]);
            }

            $templateQ = $survey->getTemplateQ();

            // Get the template questions.
            for ($i = 0; $i < $survey->getTemplateQNo(); $i++) {
                $templateQ[$i]->setText($form->get($i + 1 . "temp")->getData());
                $this->getDoctrine()->getEntityManager()->flush($templateQ[$i]);
            }

            $starQ = $survey->getStarQ();

            // Get the template questions.
            for ($i = 0; $i < $survey->getStarQNo(); $i++) {
                $starQ[$i]->setText($form->get($i + 1 . "star")->getData());
                $this->getDoctrine()->getEntityManager()->flush($starQ[$i]);
            }

            $survey->setStatus(STEP3);
            $this->getDoctrine()->getEntityManager()->flush($survey);

            return $this->redirect($this->generateUrl('survey_creation_step3'));
        }

        return $this->render("SurveyBundle:Default:surveyCreationStep2.html.twig", array("form" => $form->createView()));
    }

    public function createSurveyStep3Action() {
        $repository = $this->getDoctrine()->getRepository("SurveyBundle:Survey");

        $survey = $repository->findOneBy(array("owner" => $this->getUser(), "status" => STEP3));

        if ($survey === null) {
            return $this->redirect($this->generateUrl('survey_creation_step1'));
        }

        $form = $this->buildForm($survey);

        $form->add("Finalize", "submit", array("attr" => array("class" => "btn btn-primary")))
                ->setMethod("post")
                ->setAction($this->generateUrl("survey_creation_step3-check"));

        $form = $form->getForm();

        return $this->render("SurveyBundle:Default:surveyCreationStep3.html.twig", array("form" => $form->createView(),
            "starQ" => $survey->getStarQ()));
    }

    public function createSurveyStep3CheckAction(Request $request) {
        $repository = $this->getDoctrine()->getRepository("SurveyBundle:Survey");

        $survey = $repository->findOneBy(array("owner" => $this->getUser(), "status" => STEP3));

        if ($survey === null) {
            return $this->redirect($this->generateUrl('survey_creation_step1'));
        }

        $survey->setStatus(PUBLISHED);

        $this->getDoctrine()->getEntityManager()->flush($survey);

        $request->getSession()->getFlashBag()->add('success', 'Your survey was created you can access it at: ' . 'surveys/' . $survey->getSid());

        return $this->redirect($this->generateUrl('home'));
    }

    /**
     * @param $survey Survey
     * @return \Symfony\Component\Form\Form|\Symfony\Component\Form\FormBuilder
     */
    private function buildVoidForm($survey) {
        $form = $this->createFormBuilder(array());

        $freeText = $survey->getFreeText();

        for ($i = 0; $i < $survey->getFreeTextNo(); $i++) {
            $form->add($i + 1 . "free", "text", array(
                'constraints' => array(new NotBlank()),
                "label" => $i + 1 . " - Free text",
                "attr" => array(
                    "placeholder" => "free text question",
                    "value" =>  $freeText[$i]->getText()
                )
            ));
        }

        $templateQ = $survey->getTemplateQ();

        for ($i = 0; $i < $survey->getTemplateQNo(); $i++) {
            $form->add($i + 1 . "temp", "text", array(
                'constraints' => array(new NotBlank()),
                "label" => $i + 1 . " - Template",
                "attr" => array(
                    "placeholder" => "template question",
                    "value" =>  $templateQ[$i]->getText()
                )
            ));
        }

        $starQ = $survey->getStarQ();

        for ($i = 0; $i < $survey->getStarQNo(); $i++) {
            $form->add($i + 1 . "star", "text", array(
                'constraints' => array(new NotBlank()),
                "label" => $i + 1 . " - Star",
                "attr" => array(
                    "placeholder" => "template question",
                    "value" =>  $starQ[$i]->getText()
                )
            ));
        }

        return $form;
    }

    /**
     * @param $survey Survey
     * @return \Symfony\Component\Form\Form|\Symfony\Component\Form\FormBuilder
     */
    private function buildForm($survey) {
        $form = $this->createFormBuilder(array());

        $freeText = $survey->getFreeText();

        for ($i = 0; $i < $survey->getFreeTextNo(); $i++) {
            $form->add($i + 1 . "free", "textarea", array(
                "label" => $freeText[$i]->getText(),
                "attr" => array("placeholder" => "your answer")
            ));
        }

        $templateQ = $survey->getTemplateQ();

        for ($i = 0; $i < $survey->getTemplateQNo(); $i++) {
            $form->add($i + 1 . "temp", "choice", array(
                "choices" => array(
                    "1" => "1",
                    "2" => "2",
                    "3" => "3",
                    "4" => "4",
                    "5" => "5",
                    "6" => "6",
                    "7" => "7",
                    "8" => "8",
                    "9" => "9",
                    "10" => "10"
                ),
                "label" =>  $templateQ[$i]->getText()
            ));
        }

       /* $starQ = $survey->getStarQ();

        for ($i = 0; $i < $survey->getStarQNo(); $i++) {
            $form->add($i + 1 . "star", "text", array(
                "label" =>  $starQ[$i]->getText()
            ));
        }*/

        return $form;
    }


    public function displayMySurveysAction() {
        $repository = $this->getDoctrine()->getRepository("SurveyBundle:Survey");

        $surveys = $repository->findBy(array("owner" => $this->getUser(), "status" => array(PUBLISHED, UNPUBLISHED)));

        return $this->render("SurveyBundle:Default:mySurveys.html.twig", array("surveys" => $surveys));
    }

    public function modifySurveyAction($sid) {
        $repository = $this->getDoctrine()->getRepository("SurveyBundle:Survey");

        $survey = $repository->findOneBy(array("sid" => $sid));

        $survey->setStatus(STEP1);

        return $this->redirect($this->generateUrl('survey_creation_step1'));
    }
}