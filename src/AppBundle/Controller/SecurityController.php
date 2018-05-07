<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class SecurityController extends Controller
{
    /**
     * @Route ("/login", name="login")
     */
    public function loginAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
            'security/login.html.twig',
            array(
                // last username entered by the user
                'last_username' => $lastUsername,
                'error'
                => $error,
            )
        );
    }

    /**
     * @Route("/redirect", name="redirect")
     */
    public function redirectAction()
    {
        $version = $this->getParameter('version');
        if ($this->getUser()->getRoles()[0] == "ROLE_ENCODER" && $version == 1) {
            return $this->redirectToRoute('encoder_homepage');
        }
        else if ($this->getUser()->getRoles()[0] == "ROLE_ENCODER" && $version == 2) {
            return $this->redirectToRoute('encoder_two_homepage');
        }
        else if ($this->getUser()->getRoles()[0] == "ROLE_ADMIN" && $version == 1){
            return $this->redirectToRoute('admin_homepage');
        }
        else if ($this->getUser()->getRoles()[0] == "ROLE_ADMIN" && $version == 2){
            return $this->redirectToRoute('admin_two_homepage');
        }
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {

    }
}
