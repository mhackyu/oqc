<?php
/**
 * Created by PhpStorm.
 * User: mhackyu
 * Date: 5/3/18
 * Time: 2:50 PM
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CandidateRepository")
 * @ORM\Table(name="candidate")
 */
class Candidate
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
    private $name;
    /**
     * @ORM\Column(type="string")
     */
    private $nickname;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Position", inversedBy="candidate")
     */
    private $position;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Vote", mappedBy="candidate")
     */
    private $vote;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\VoteTwo", mappedBy="candidate")
     */
    private $voteTwo;

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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * @param mixed $nickname
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;
    }

    /**
     * @return mixed
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param mixed $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
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