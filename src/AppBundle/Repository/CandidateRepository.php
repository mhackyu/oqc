<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class CandidateRepository extends EntityRepository
{
    public function findAllCandidatesByPosition()
    {
        return $this->getEntityManager()
            ->createQuery('
                SELECT c, p.id, p.name
                FROM AppBundle:Candidate c
                JOIN c.position p 
                ORDER BY p.level ASC, c.name ASC
            ')
            ->getResult();
    }
}