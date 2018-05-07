<?php
/**
 * Created by PhpStorm.
 * User: mhackyu
 * Date: 5/7/18
 * Time: 2:14 PM
 */

namespace AppBundle\Repository;
use Doctrine\ORM\EntityRepository;

class ClusterRepository extends EntityRepository
{
    public function getAllPrecintOfCluster($cluster) {
        return $this->getEntityManager()
            ->createQuery('
                SELECT p
                FROM AppBundle:Precint p 
                WHERE p.cluster = :cluster
            ')
            ->setParameter('cluster', $cluster)
            ->getResult();
    }
}