<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Cluster;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        return $this->redirectToRoute('login');
    }

    /**
     * @Route("/add/precints")
     */
    public function addPrecintsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $location = $em->getRepository('AppBundle:Location')
            ->find(1);
        $cluster = new Cluster();
        $cluster->setNumber(227);
        $cluster->setGroupPrecints(["0275A","0275B"]);
        $cluster->setTotalVoters(385);
        $cluster->setLocation($location);

        $em->persist($cluster);
        $em->flush();

        return new Response("done");
    }

    /**
     * @Route("add/update")
     */
    public function updateClusterAction()
    {
        $em = $this->getDoctrine()->getManager();
        $cluster = $em->getRepository('AppBundle:Cluster')
            ->find(3);

        $cluster->setGroupPrecints([4,5]);
        $em->flush();

        return $this->redirectToRoute("encoder_homepage");
    }

}
