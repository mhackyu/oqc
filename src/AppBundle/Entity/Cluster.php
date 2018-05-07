<?php
/**
 * Created by PhpStorm.
 * User: mhackyu
 * Date: 5/3/18
 * Time: 2:18 PM
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ClusterRepository")
 * @ORM\Table(name="cluster")
 */
class Cluster
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
     * @ORM\Column(type="array", nullable=true)
     */
    private $groupPrecints;
    /**
     * @ORM\Column(type="string")
     */
    private $totalVoters;
    /**
     * @ORM\Column(type="boolean")
     */
    private $isDone;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Location", inversedBy="cluster")
     */
    private $location;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Precint", mappedBy="cluster")
     */
    private $precint;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\VoteTwo", mappedBy="cluster")
     */
    private $voteTwo;

    public function __construct()
    {
        $this->isDone = false;
    }

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
    public function getGroupPrecints()
    {
        return $this->groupPrecints;
    }

    /**
     * @param mixed $groupPrecints
     */
    public function setGroupPrecints($groupPrecints)
    {
        $this->groupPrecints = $groupPrecints;
    }

    /**
     * @return mixed
     */
    public function getTotalVoters()
    {
        return $this->totalVoters;
    }

    /**
     * @param mixed $totalVoters
     */
    public function setTotalVoters($totalVoters)
    {
        $this->totalVoters = $totalVoters;
    }

    /**
     * @return mixed
     */
    public function getisDone()
    {
        return $this->isDone;
    }

    /**
     * @param mixed $isDone
     */
    public function setIsDone($isDone)
    {
        $this->isDone = $isDone;
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
    public function getPrecint()
    {
        return $this->precint;
    }

    /**
     * @param mixed $precint
     */
    public function setPrecint($precint)
    {
        $this->precint = $precint;
    }

    /**
     * @return mixed
     */
    public function getVoteTwo()
    {
        return $this->voteTwo;
    }

    /**
     * @param mixed $voteTwo
     */
    public function setVoteTwo($voteTwo)
    {
        $this->voteTwo = $voteTwo;
    }


}