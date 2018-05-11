<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Candidate;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Spipu\Html2Pdf\Html2Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ReportController extends Controller
{
    /**
     * @Route("/admin/report/overall-ranking", name="report_overall_ranking")
     */
    public function ORAction()
    {
        $date = new \DateTime();
        $em = $this->getDoctrine()->getManager();

        $punongBrgy = $em->getRepository('AppBundle:VoteTwo')
            ->getVotesOfAllCandidatesPerPosition(1);

        $brgyKagawad = $em->getRepository('AppBundle:VoteTwo')
            ->getVotesOfAllCandidatesPerPosition(2);

        $skChairman = $em->getRepository('AppBundle:VoteTwo')
            ->getVotesOfAllCandidatesPerPosition(3);

        $skKagawad = $em->getRepository('AppBundle:VoteTwo')
            ->getVotesOfAllCandidatesPerPosition(4);

        $html2pdf = new Html2Pdf('P', 'Legal', 'en', false, 'UTF-8');
        $html2pdf->setDefaultFont('Arial');
        $html = $this->renderView('report/overall_rank.html.twig', [
            'punongBrgy' => $punongBrgy,
            'brgyKagawad' => $brgyKagawad,
            'skChairman' => $skChairman,
            'skKagawad' => $skKagawad,
            'date' => $date
        ]);

//        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->pdf->setTitle("OQC-Overall-Ranking");

        $html2pdf->writeHTML($html);

//        return $html2pdf->output();
//        return $html2pdf->output($this->getUser()->getDepartment()->getName() . ' Appropriation ' . $ppmp->getYear().'.pdf','D');
        return $html2pdf->output('OQC-Overall-Ranking.pdf');
    }

    /**
     * @Route("/admin/report/{id}/vote-information", name="report_vote_info")
     */
    public function voteInfoAction(Candidate $candidate)
    {
        $date = new \DateTime();
        $em = $this->getDoctrine()->getManager();

        $cl = $em->getRepository('AppBundle:Cluster')
            ->getAllClusters();

        $saes = $em->getRepository('AppBundle:VoteTwo')
            ->getVotesOfCandidatePerLocation($candidate->getId(),1);
        $saesWithVoters = $this->addVoters($saes, $cl);

        $adelina = $em->getRepository('AppBundle:VoteTwo')
            ->getVotesOfCandidatePerLocation($candidate->getId(),2);
        $adelinaWithVoters = $this->addVoters($adelina, $cl);

        $usp = $em->getRepository('AppBundle:VoteTwo')
            ->getVotesOfCandidatePerLocation($candidate->getId(),3);
        $uspWithVoters = $this->addVoters($usp, $cl);

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

        $html2pdf = new Html2Pdf('P', 'Legal', 'en', false, 'UTF-8');
        $html2pdf->setDefaultFont('Arial');
        $html = $this->renderView('report/vote_info.html.twig', [
            'candidate' => $candidate,
            'clusters' => $clusters,
            'totalVotes' => $totalVotes,
            'date' => $date
        ]);

        $html2pdf->pdf->setTitle("OQC-Vote-Information");
        $html2pdf->writeHTML($html);

        return $html2pdf->output('OQC-Vote-Information.pdf');
    }

    /**
     * This will add number of voters per cluster
     * @param $saes
     * @param $cl
     * @return array
     */
    private function addVoters($loc , $cl)
    {
        $locWithVoters = [];
        $totalVotes = 0;
        foreach ($loc as $value) {
            foreach ($cl as $clusterWithVoters) {
                if ($clusterWithVoters['number'] == $value['cl_number']) {
                    $value['totalVoters'] = $clusterWithVoters['totalVoters'];
                    $locWithVoters [] = $value;
                    $totalVotes += $value['votes'];
                }
            }
        }

        return $locWithVoters;
    }
}
