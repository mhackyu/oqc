<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Cluster;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiController extends Controller
{
    /**
     * This will call
     * @Route("/api/client/{id}/view-votes")
     */
    public function viewAction(Cluster $cluster)
    {
        $em = $this->getDoctrine()->getManager();
        $precints = $em->getRepository('AppBundle:Cluster')
            ->getAllPrecintOfCluster($cluster);
        // Get all candidate and their vote per precint if existing
        $candidates = $em->getRepository('AppBundle:VoteTwo')
            ->apiGetCadidateVoteByCluster($cluster);

        return new JsonResponse([
            'cluster' => [
                'number' => $cluster->getNumber(),
                'total_voters' => $cluster->getTotalVoters()
            ],
            'candidates' => $candidates
        ]);

        return $this->render('encoderTwo/update.html.twig', [
            'cluster' => $cluster,
            'precints' => $precints,
            'candidates' => $candidates,
        ]);
    }
}
