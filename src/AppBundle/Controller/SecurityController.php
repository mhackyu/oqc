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
        if ($this->getUser()->getRoles()[0] == "ROLE_ENCODER") {
            return $this->redirectToRoute('encoder_homepage');
        }
        else if ($this->getUser()->getRoles()[0] == "ROLE_ADMIN"){
//            return $this->redirectToRoute('admin_index');
            return $this->redirectToRoute('admin_homepage');
        }
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {

    }
}
