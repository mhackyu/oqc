<?php
/**
 * Created by PhpStorm.
 * User: mhackyu
 * Date: 5/4/18
 * Time: 2:13 PM
 */

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;


class VoteRepository extends  EntityRepository
{
    public function getCadidateVoteByPrecint($precint) {
        return $this->getEntityManager()
            ->createQuery('
                SELECT c, v.count as vote, p.id, p.name
                FROM AppBundle:Candidate c
                LEFT JOIN AppBundle:Vote v
                WHERE c.id = v.candidate AND v.precint = :precint
                JOIN c.position p 
                ORDER BY p.level ASC, c.name ASC
            ')
            ->setParameter('precint',$precint )
            ->getResult();
    }

    public function getCandidateVoteByLocation($precint) {
        return $this->getEntityManager()
            ->createQuery('
                SELECT c, v.count as vote, pos.id, pos.name
                FROM AppBundle:Candidate c
                LEFT JOIN AppBundle:Vote v
                WHERE c.id = v.candidate AND v.precint = :precint
                JOIN c.position pos
                ORDER BY pos.level ASC, c.name ASC
            ')
            ->setParameter('precint',$precint )
            ->getResult();
    }

    public function getVotesOfCandidatePerPrecint()
    {
        return $this->getEntityManager()
            ->createQuery('
                SELECT c, p.id, p.name, v.count
                FROM AppBundle:Candidate c
                JOIN c.position p 
                INNER JOIN AppBundle:Vote v 
                WHERE c.id = v.candidate AND v.precint = 1
                ORDER BY p.level ASC, c.name ASC
            ')
            ->getResult();
    }

    public function getVotesOfCandidate($candidate)
    {
         $votes = $this->getEntityManager()
            ->createQuery('
                SELECT SUM(v.count) as votes
                FROM AppBundle:Vote v
                WHERE v.candidate = :candidate
            ')
            ->setParameter("candidate", $candidate)
            ->getResult();

         return $votes[0]['votes'];
    }

    /**
     * Legit working to <3
     * @param $location
     * @return array
     */
    public function getVotesOfAllCandidatesPerLocation($location)
    {
        return $this->getEntityManager()
            ->createQuery('
                SELECT c.id, c.name , c.nickname, p.id as loc_id, p.name as pos_name, SUM (v.count) as votes
                FROM AppBundle:Vote v
                JOIN v.precint pr
                JOIN v.candidate c
                JOIN c.position p 
                WHERE pr.location = :location
                GROUP BY c.id
                ORDER BY p.level ASC, votes DESC
            ')
            ->setParameter('location', $location)
            ->getResult();
    }

    /**
     * Legit na mag wo-work din to <3
     * @return array
     */
    public function getVotesOfAllCandidates()
    {
        return $this->getEntityManager()
            ->createQuery('
                SELECT c.id, c.name , c.nickname, p.id as loc_id, p.name as pos_name, SUM (v.count) as votes
                FROM AppBundle:Vote v
                JOIN v.precint pr
                JOIN v.candidate c
                JOIN c.position p 
                GROUP BY c.id
                ORDER BY p.level ASC, votes DESC
            ')
            ->getResult();
    }

    public function getVotesOfCandidatePerLocation($candidate)
    {
        return $this->getEntityManager()
            ->createQuery('
                SELECT pr.number, SUM(v.count) as votes
                FROM AppBundle:Vote v
                JOIN v.precint pr
                LEFT JOIN pr.location l
                WHERE v.candidate = :candidate
                GROUP BY pr.number
            ')
            ->setParameter('candidate', $candidate)
            ->getResult();
    }
}