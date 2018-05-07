<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Candidate;
use AppBundle\Entity\Location;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminTwoController extends Controller
{
    /**
     * @Route("/admin-2", name="admin_two_homepage")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $candidates = $em->getRepository('AppBundle:VoteTwo')
            ->getVotesOfAllCandidates();

        return $this->render('adminTwo/index.html.twig', [
            'candidates' => $candidates
        ]);
    }

    /**
     * @Route("/admin-2/votes-by-location", name="admin_two_votes_by_location")
     */
    public function votesByLocationAction()
    {
        $em = $this->getDoctrine()->getManager();
        $locations = $em->getRepository('AppBundle:Location')
            ->findAll();

        return $this->render('adminTwo/votesByLoc.html.twig', [
            'locations' => $locations
        ]);
    }

    /**
     * @Route("/admin-2/{id}/votes-by-location", name="admin_two_votes_by_location_status")
     */
    public function statusAction(Location $location)
    {
        $em = $this->getDoctrine()->getManager();

        $candidates = $em->getRepository('AppBundle:VoteTwo')
            ->getVotesOfAllCandidatesPerLocation($location->getId());

        return $this->render('adminTwo/status.html.twig', [
            'location' => $location,
            'candidates' => $candidates
        ]);
    }

    /**
     * @Route("/admin-2/{id}/vote-information", name="admin_two_vote_info")
     */
    public function voteInfoAction(Candidate $candidate)
    {
        $em = $this->getDoctrine()->getManager();

        $saes = $em->getRepository('AppBundle:VoteTwo')
            ->getVotesOfCandidatePerLocation($candidate->getId(),1);
        $adelina = $em->getRepository('AppBundle:VoteTwo')
            ->getVotesOfCandidatePerLocation($candidate->getId(),2);
        $usp = $em->getRepository('AppBundle:VoteTwo')
            ->getVotesOfCandidatePerLocation($candidate->getId(),3);

        $clusters = [
            [
                'loc_name' => 'San Antonio',
                'clusters' => $saes
            ],
            [
                'loc_name' => 'Adelina I Elementary School',
                'clusters' => $adelina
            ],
            [
                'loc_name' => 'United San Pedro Subdivision Multi-purpose Hall',
                'clusters' => $usp
            ],

        ];

        $totalVotes = $em->getRepository('AppBundle:VoteTwo')
            ->getTotalVotesPerCandidate($candidate->getId());

//        dump($clusters);die;

        return $this->render('adminTwo/voteInfo.html.twig', [
            'candidate' => $candidate,
            'clusters' => $clusters,
            'totalVotes' => $totalVotes
        ]);
    }
}
