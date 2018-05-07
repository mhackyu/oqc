<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Precint;
use AppBundle\Entity\Vote;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class EncodeController extends Controller
{
    /**
     * @Route("/encode", name="encoder_homepage")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $clusters = $em->getRepository('AppBundle:Cluster')
            ->findAll();
        $precints = $em->getRepository('AppBundle:Precint')
            ->findBy([
                'location' => $this->getUser()->getLocation()
            ]);

        return $this->render('encoder/index.html.twig', [
            'location' => $this->getUser()->getLocation()->getName(),
            'clusters' => $clusters,
            'precints' => $precints
        ]);
    }

    /**
     * @Route("/encode/{id}/update", name="encoder_update")
     */
    public function updateAction(Precint $precint)
    {
        $em = $this->getDoctrine()->getManager();
        // Get all candidate and their vote per precint if existing
        $candidates = $em->getRepository('AppBundle:Vote')
            ->getCadidateVoteByPrecint($precint);

//        $this->addFlash('success', 'Successfully');

        return $this->render('encoder/update.html.twig', [
            'precint' => $precint,
            'candidates' => $candidates,
        ]);
    }

    /**
     * @Route("/encode/add-vote", name="encoder_add_vote")
     * TODO: Please validate maximum votes per precints.
     */
    public function addVoteAction(Request $request)
    {
        if ($request->isMethod("POST")) {
            $em = $this->getDoctrine()->getManager();
            $precint = $em->getRepository('AppBundle:Precint')
                ->findOneBy(['number' => $request->request->get("precint")]);

            if(!$this->isCsrfTokenValid('new', $request->request->get('voteToken'))) {
                $this->addFlash('danger', "Can't add votes because of invalid token.");
                return $this->redirectToRoute('encoder_update', ['id' => $precint->getId()]);
            }

            $votes = $request->request->get("votes");
            $user = $this->getUser();

            if ($precint->hasVotes()) {
                // Update votes of candidates per precint.
                foreach ($votes as $id => $value) {
                    $candidate = $em->getRepository('AppBundle:Candidate')
                        ->find($id);
                    $vote = $em->getRepository('AppBundle:Vote')
                        ->findOneBy(['candidate' => $candidate, 'precint' => $precint]);
                    $vote->setCount($value);
                }
                $this->addFlash('success', 'Votes successfully updated.');
            }
            else {
                // Add votes of candidates in precint.
                foreach ($votes as $id => $value) {
                    $candidate = $em->getRepository('AppBundle:Candidate')
                        ->find($id);
                    $vote = New Vote();
                    $vote->setPrecint($precint);
                    $vote->setUser($user);
                    $vote->setCandidate($candidate);
                    $vote->setCount($value);
                    $em->persist($vote);
                }

                $precint->setHasVotes(true);
                $this->addFlash('success', 'Votes successfully added.');
            }

            $em->flush();
        }

        return $this->redirectToRoute("encoder_update", [ 'id' => $precint->getId()]);
    }
}
