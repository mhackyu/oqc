<?php
/**
 * Created by PhpStorm.
 * User: mhackyu
 * Date: 5/3/18
 * Time: 7:37 PM
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="precint")
 */
class Precint
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string")
     */
    private $number;
    /**
     * @ORM\Column(type="string")
     */
    private $totalVoters;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Location", inversedBy="precint")
     */
    private $location;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Cluster", inversedBy="precint")
     */
    private $cluster;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Vote", mappedBy="precint")
     */
    private $vote;

    /**
     * @ORM\Column(type="boolean")
     */
    private $hasVotes;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param mixed $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /**
     * @return mixed
     */
    public function getTotalVoters()
    {
        return $this->totalVoters;
    }

    /**
     * @param mixed $totalVotes
     */
    public function setTotalVoters($totalVoters)
    {
        $this->totalVoters = $totalVotes;
    }

    /**
     * @return mixed
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param mixed $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @return mixed
     */
    public function getCluster()
    {
        return $this->cluster;
    }

    /**
     * @param mixed $cluster
     */
    public function setCluster($cluster)
    {
        $this->cluster = $cluster;
    }

    /**
     * @return mixed
     */
    public function getVote()
    {
        return $this->vote;
    }

    /**
     * @param mixed $vote
     */
    public function setVote($vote)
    {
        $this->vote = $vote;
    }

    /**
     * @return mixed
     */
    public function hasVotes()
    {
        return $this->hasVotes;
    }

    /**
     * @param mixed $hasVotes
     */
    public function setHasVotes($hasVotes)
    {
        $this->hasVotes = $hasVotes;
    }




}