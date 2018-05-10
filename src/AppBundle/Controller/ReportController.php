<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Spipu\Html2Pdf\Html2Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ReportController extends Controller
{
    /**
     * @Route("/admin/report/overall-ranking", name="report_overall_ranking")
     */
    public function indexAction()
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
}
