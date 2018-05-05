<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Candidate;
use AppBundle\Entity\Location;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminController extends Controller
{
    /**
     * @Route("/admin", name="admin_homepage")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $candidates = $em->getRepository('AppBundle:Vote')
            ->getVotesOfAllCandidates();

        return $this->render('admin/index.html.twig', [
            'candidates' => $candidates
        ]);
    }

    /**
     * @Route("/admin/votes-by-location", name="admin_votes_by_location")
     */
    public function votesByLocationAction()
    {
        $em = $this->getDoctrine()->getManager();
        $locations = $em->getRepository('AppBundle:Location')
            ->findAll();

        return $this->render('admin/votesByLoc.html.twig', [
            'locations' => $locations
        ]);
    }

    /**
     * @Route("/admin/{id}/votes-by-location", name="admin_votes_by_location_status")
     */
    public function statusAction(Location $location)
    {
        $em = $this->getDoctrine()->getManager();

        $candidates = $em->getRepository('AppBundle:Vote')
            ->getVotesOfAllCandidatesPerLocation($location->getId());

        return $this->render('admin/status.html.twig', [
            'location' => $location,
            'candidates' => $candidates
        ]);
    }

    /**
     * @Route("/admin/{id}/vote-information", name="admin_vote_info")
     */
    public function voteInfoAction(Candidate $candidate)
    {
        $em = $this->getDoctrine()->getManager();
        $precints = $em->getRepository('AppBundle:Vote')
            ->getVotesOfCandidatePerLocation($candidate->getId());

        dump($precints);die;

        return $this->render('admin/voteInfo.html.twig', [
            'candidate' => $candidate
        ]);
    }
}
