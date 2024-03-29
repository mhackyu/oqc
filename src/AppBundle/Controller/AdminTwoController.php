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

        $clusters = $em->getRepository('AppBundle:Cluster')
            ->findAll();
        $doneClusters = count($em->getRepository('AppBundle:Cluster')
            ->findBy(['isDone' => true]));
        $totalClusters = count($clusters);
        $percentageStatus = ($doneClusters/$totalClusters)*100;
        $percentageRemaining = 100 - $percentageStatus;

        return $this->render('adminTwo/index.html.twig', [
            'candidates' => $candidates,
            'totalClusters' => $totalClusters,
            'percentageStatus' => $percentageStatus,
            'remainingPercentage' => $percentageRemaining,
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

        $percentage = $em->getRepository('AppBundle:VoteTwo')
            ->getPercentagePerCluster();

        $cl = $em->getRepository('AppBundle:Cluster')
            ->getAllClusters();

        $saes = $em->getRepository('AppBundle:VoteTwo')
            ->getVotesOfCandidatePerLocation($candidate->getId(),1);

        $saesWithVoters = $this->addVoters($saes, $cl, $percentage);

        $adelina = $em->getRepository('AppBundle:VoteTwo')
            ->getVotesOfCandidatePerLocation($candidate->getId(),2);
        $adelinaWithVoters = $this->addVoters($adelina, $cl, $percentage);

        $usp = $em->getRepository('AppBundle:VoteTwo')
            ->getVotesOfCandidatePerLocation($candidate->getId(),3);
        $uspWithVoters = $this->addVoters($usp, $cl, $percentage);

        $clusters = [
            [
                'loc_name' => 'San Antonio',
                'clusters' => $saesWithVoters,
            ],
            [
                'loc_name' => 'Adelina I Elementary School',
                'clusters' => $adelinaWithVoters
            ],
            [
                'loc_name' => 'United San Pedro Subdivision Multi-purpose Hall',
                'clusters' => $uspWithVoters
            ],

        ];

        $totalVotes = $em->getRepository('AppBundle:VoteTwo')
            ->getTotalVotesPerCandidate($candidate->getId());

        return $this->render('adminTwo/voteInfo.html.twig', [
            'candidate' => $candidate,
            'clusters' => $clusters,
            'totalVotes' => $totalVotes,
        ]);
    }

    /**
     * @Route("/admin/cluster-status", name="admin_cluster_status")
     */
    public function clusterStatusAction()
    {
        $em = $this->getDoctrine()->getManager();
        $clusters = $em->getRepository('AppBundle:Cluster')
            ->findAll();
        $doneClusters = count($em->getRepository('AppBundle:Cluster')
            ->findBy(['isDone' => true]));
        $totalClusters = count($clusters);
        $percentageStatus = ($doneClusters/$totalClusters)*100;
        $percentageRemaining = 100 - $percentageStatus;

        return $this->render('adminTwo/cluster_status.html.twig', [
            'clusters' => $clusters,
            'doneClusters' => $doneClusters,
            'totalClusters' => $totalClusters,
            'percentageStatus' => $percentageStatus,
            'remainingPercentage' => $percentageRemaining
        ]);
    }

    /**
     * This will add number of voters per cluster
     * @param $saes
     * @param $cl
     * @return array
     */
    private function addVoters($loc , $cl, $percentage)
    {
        $locWithVoters = [];
        $totalVotes = 0;
        foreach ($loc as $value) {
            foreach ($cl as $clusterWithVoters) {
                if ($clusterWithVoters['number'] == $value['cl_number']) {
                    $value['totalVoters'] = $clusterWithVoters['totalVoters'];
                    $value['overallVotes'] = $percentage[$value['cl_number']];
                    $locWithVoters [] = $value;
                    $totalVotes += $value['votes'];
                }
            }
        }

        return $locWithVoters;
    }
}
