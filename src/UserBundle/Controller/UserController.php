<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use UserBundle\Entity\User;
use UserBundle\Form\LogIn;
use UserBundle\Form\SignUp;

class UserController extends Controller {


    public function HomeAction() {
        return $this->render("UserBundle:Default:home.html.twig");
    }

    /**
     * checks if a username already exists
     * @param Request $request
     * @return Response
     */
    public function CheckUsernameAction(Request $request) {

        $repository = $this->getDoctrine()->getRepository("UserBundle:User");

        $username = $request->request->get('username');

        $ok = $repository->findOneByUsername($username);

        if ($ok === null)
            $response = new Response("Ok, utilizatorul nu exista", Response::HTTP_OK);
        else
            $response = new Response("Not ok, utilizatorul exista", 404);

        return $response;
    }

    /**
     * displays the sign up form
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function SignUpFormDisplayAction() {

        /* if the user is already authentificated */
        if ($this->getUser() !== null) {
            return $this->redirect($this->generateUrl("home"));
        }

        $user = new User();

        $form = $this->createForm(new SignUp(), $user, array(
            "action" => $this->generateUrl("sign_up_process"),
            "method" => "POST"
        ));

        return $this->render("UserBundle:Default:signUp.html.twig", array("form" => $form->createView()));
    }

    /**
     * creates a new account
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function SignUpFormHandleAction(Request $request) {

        /* if the user is already authentificated */
        if ($this->getUser() !== null) {
            return $this->redirect($this->generateUrl("home"));
        }

        $form = $this->createForm(new SignUp(), new User());

        $form->handleRequest($request);

        if($form->isValid()) {
            $user = $form->getData();

            $repository = $this->getDoctrine()->getRepository("UserBundle:User");

            /* checks if there are other entries with this username in the database */
            $error = $repository->findOneByUsername($user->getUsername());

            /* if yes, it notifies the user */
            if ($error !== null) {
                $request->getSession()->getFlashBag()->add("error", "The user alreay exists!");

                return $this->render("UserBundle:Default:signUp.html.twig", array("form" => $form->createView()));
            }

            /* checks if there are other entries with this email in the database */
            $error = $repository->findOneByEmail($user->getEmail());

            /* if yes, it notifies the user */
            if ($error !== null) {
                $request->getSession()->getFlashBag()->add("error", "There is already an user with this email!");

                return $this->render("UserBundle:Default:signUp.html.twig", array("form" => $form->createView()));
            }

            /* encoding the password with the alogrithm specified in security.yml */
            // maybe later
            /*$encoder = $this->container->get("security.password_encoder");
            $encoded = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($encoded);*/

            $em = $this->getDoctrine()->getManager();

            $em->persist($user);

            $em->flush();

            $request->getSession()->getFlashBag()->add('success', "Your account was created, you can now login!");

            return $this->redirect($this->generateUrl("log_in"));
        }

        return $this->render("UserBundle:Default:signUp.html.twig", array("form" => $form->createView()));
    }

    /**
     * displays the log in form, the handling is done automatically
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function LogInFormDisplayAction(Request $request) {

        /* if the user is already authenticated */
        if ($this->getUser() !== null) {
            return $this->redirect($this->generateUrl("home"));
        }

        $user = new User();

        $form = $this->createForm(new LogIn(), $user, array(
            "action" => $this->generateUrl("log_in_process"),
            "method" => "POST"
        ));

        $authenticationUtils = $this->get('security.authentication_utils');

        /* gets the login error if there is one */
        $error = $authenticationUtils->getLastAuthenticationError();


        if ($error !== null) {
            $request->getSession()->getFlashBag()->add("error", "User or password mismatch!");
        }

        return $this->render("UserBundle:Default:logIn.html.twig", array("form" => $form->createView()));
    }
}