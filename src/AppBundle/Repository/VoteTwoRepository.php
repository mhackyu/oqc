<?php
/**
 * Created by PhpStorm.
 * User: mhackyu
 * Date: 5/7/18
 * Time: 2:27 PM
 */

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class VoteTwoRepository extends EntityRepository
{
    public function getCadidateVoteByCluster($cluster) {
        return $this->getEntityManager()
            ->createQuery('
                SELECT c, v.count as vote, p.id, p.name
                FROM AppBundle:Candidate c
                LEFT JOIN AppBundle:VoteTwo v
                WHERE c.id = v.candidate AND v.cluster = :cluster
                JOIN c.position p 
                ORDER BY p.level ASC, c.name ASC
            ')
            ->setParameter('cluster',$cluster )
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
                FROM AppBundle:VoteTwo v
                JOIN v.cluster pr
                JOIN v.candidate c
                JOIN c.position p 
                GROUP BY c.id
                ORDER BY p.level ASC, votes DESC
            ')
            ->getResult();
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
                FROM AppBundle:VoteTwo v
                JOIN v.cluster pr
                JOIN v.candidate c
                JOIN c.position p 
                WHERE pr.location = :location
                GROUP BY c.id
                ORDER BY p.level ASC, votes DESC
            ')
            ->setParameter('location', $location)
            ->getResult();
    }

    public function getVotesOfCandidatePerLocation($candidate, $location)
    {
        return $this->getEntityManager()
            ->createQuery('
                SELECT pr.number as cl_number, SUM(v.count) as votes
                FROM AppBundle:VoteTwo v
                JOIN v.cluster pr
                WHERE v.candidate = :candidate AND pr.location = :location
                GROUP BY pr.number
            ')
            ->setParameter('candidate', $candidate)
            ->setParameter('location', $location)
            ->getResult();
    }

    public function getTotalVotesPerCandidate($candidate)
    {
        $votes = $this->getEntityManager()
            ->createQuery('
                SELECT SUM(v.count)
                FROM AppBundle:VoteTwo v
                WHERE v.candidate = :candidate
            ')
            ->setParameter('candidate', $candidate)
            ->getResult();
        return $votes[0][1];
    }
}