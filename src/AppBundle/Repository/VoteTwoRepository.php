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
     * This will get all votes of candidate on all locations.
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

    /**
     * API repository to get all votes of candidates per cluster.
     * @param $cluster
     * @return array
     */
    public function apiGetCadidateVoteByCluster($cluster) {
        return $this->getEntityManager()
            ->createQuery('
                SELECT c.id, c.name, c.nickname, v.count as vote, p.id as pos_id, p.name as pos_name
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
     * API repository to update vote of candidate per cluster.
     * @param $cluster
     * @return array
     */
    public function apiUpdateVoteOfCandidatePerCluster($cluster, $candidate, $count) {
        return $this->getEntityManager()
            ->createQuery('
                UPDATE AppBundle:VoteTwo v
                SET v.count = :count
                WHERE v.cluster = :cluster and v.candidate = :candidate
            ')
            ->setParameter('cluster', $cluster)
            ->setParameter('candidate', $candidate)
            ->setParameter('count', $count)
            ->getResult();
    }
}