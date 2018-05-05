<?php
/**
 * Created by PhpStorm.
 * User: mhackyu
 * Date: 5/4/18
 * Time: 11:35 AM
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\VoteRepository")
 * @ORM\Table(name="vote")
 */
class Vote
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Candidate", inversedBy="vote")
     */
    private $candidate;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Precint", inversedBy="vote")
     */
    private $precint;
    /**
     * @ORM\Column(type="string")
     */
    private $count;
    /**
     * @ORM\Column(type="datetime")
     */
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="vote")
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
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
    public function getCandidate()
    {
        return $this->candidate;
    }

    /**
     * @param mixed $candidate
     */
    public function setCandidate($candidate)
    {
        $this->candidate = $candidate;
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
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param mixed $count
     */
    public function setCount($count)
    {
        $this->count = $count;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }


}