<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Cluster;
use AppBundle\Entity\Precint;
use AppBundle\Entity\VoteTwo;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class EncoderTwoController extends Controller
{
    /**
     * @Route("/encode-2", name="encoder_two_homepage")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $clusters = $em->getRepository('AppBundle:Cluster')
            ->findAll();
        $precints = $em->getRepository('AppBundle:Precint')
            ->findAll();

        return $this->render('encoderTwo/index.html.twig', [
            'location' => $this->getUser()->getLocation()->getName(),
            'clusters' => $clusters,
            'precints' => $precints
        ]);
    }

    /**
     * @Route("/encode-2/{id}/update", name="encoder_two_update")
     */
    public function updateAction(Cluster $cluster)
    {
        $em = $this->getDoctrine()->getManager();
        $precints = $em->getRepository('AppBundle:Cluster')
            ->getAllPrecintOfCluster($cluster);
        // Get all candidate and their vote per precint if existing
        $candidates = $em->getRepository('AppBundle:VoteTwo')
            ->getCadidateVoteByCluster($cluster);

        return $this->render('encoderTwo/update.html.twig', [
            'cluster' => $cluster,
            'precints' => $precints,
            'candidates' => $candidates,
        ]);
    }

    /**
     * @Route("/encode-2/add-vote", name="encoder_two_add_vote")
     * TODO: Please validate maximum votes per precints.
     */
    public function addVoteAction(Request $request)
    {
        if ($request->isMethod("POST")) {
            $em = $this->getDoctrine()->getManager();
            $cluster = $em->getRepository('AppBundle:Cluster')
                ->findOneBy(['number' => $request->request->get("cluster")]);

            if(!$this->isCsrfTokenValid('new', $request->request->get('voteToken'))) {
                $this->addFlash('danger', "Can't add votes because of invalid token.");
                return $this->redirectToRoute('encoder_update', ['id' => $cluster->getId()]);
            }

            $votes = $request->request->get("votes");
            $user = $this->getUser();

            if ($cluster->getIsDone()) {
                // Update votes of candidates per precint.
                foreach ($votes as $id => $value) {
                    $candidate = $em->getRepository('AppBundle:Candidate')
                        ->find($id);
                    $vote = $em->getRepository('AppBundle:Vote')
                        ->findOneBy(['candidate' => $candidate, 'precint' => $cluster]);
                    $vote->setCount($value);
                }
                $this->addFlash('success', 'Votes successfully updated.');
            }
            else {
                // Add votes of candidates in precint.
                foreach ($votes as $id => $value) {
                    $candidate = $em->getRepository('AppBundle:Candidate')
                        ->find($id);
                    $vote = New VoteTwo();
                    $vote->setCluster($cluster);
                    $vote->setUser($user);
                    $vote->setCandidate($candidate);
                    $vote->setCount($value);
                    $em->persist($vote);
                }

                $cluster->setIsDone(true);
                $this->addFlash('success', 'Votes successfully added.');
            }

            $em->flush();
        }

        return $this->redirectToRoute("encoder_two_update", [ 'id' => $cluster->getId()]);
    }
}
