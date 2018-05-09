<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Candidate;
use AppBundle\Entity\Cluster;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ApiController extends Controller
{
    /**
     * This function will return a json response of candidate votes per cluster.
     * @Route("/api/client/view-votes", name="api_client_view_votes")
     */
    public function viewAction(Request $request)
    {
        if ($request->isMethod("GET")) {
            $em = $this->getDoctrine()->getManager();
            $cluster = $em->getRepository('AppBundle:Cluster')
                ->find($request->get('cluster'));
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
        }
    }

    /**
     * This function will update vote of candidate per cluster.
     * @Route("/api/client/update-votes", name="api_client_update_votes")
     */
    public function updateVotesByAction(Request $request)
    {
        if ($request->isMethod("POST")) {
            $clusterId = $request->request->get('cluster');
            $candidateId = $request->request->get('candidate');
            $count = $request->request->get('count');

            $em = $this->getDoctrine()->getManager();

            // Update candidate vote in cluster.
            $success = $em->getRepository('AppBundle:VoteTwo')
                ->apiUpdateVoteOfCandidatePerCluster($clusterId, $candidateId, $count);

            return new JsonResponse([
                'isSuccess' => $success
            ]);
        }

        return new JsonResponse([
            'isSuccess' => 0
        ]);
    }

    /**
     * This function will update vote of candidate per cluster.
     * @Route("/api/admin/overall-ranking", name="api_admin_overall_ranking")
     */
    public function candidateRankingAction(Request $request)
    {
        if ($request->isMethod("GET")) {
            $em = $this->getDoctrine()->getManager();

            $candidates = $em->getRepository('AppBundle:VoteTwo')
                ->getVotesOfAllCandidates();

            return new JsonResponse([
                'candidates' => $candidates
            ]);
        }
    }

    /**
     * This function will return vote information of candidate.
     * @Route("/api/admin/vote-information", name="api_admin_vote_information")
     */
    public function voteInfoAction(Request $request)
    {
        if ($request->isMethod("GET")) {
            $em = $this->getDoctrine()->getManager();
            $candidate = $em->getRepository('AppBundle:Candidate')
                ->find($request->get('candidate'));

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

            return new JsonResponse([
                'candidate' => [
                    'name' => $candidate->getName(),
                    'position' => $candidate->getPosition()->getName()
                ],
                'clusters' => $clusters,
                'totalVotes' => $totalVotes
            ]);

        }
    }

}
