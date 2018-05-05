<?php
/**
 * Created by PhpStorm.
 * User: mhackyu
 * Date: 5/3/18
 * Time: 2:05 PM
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="location")
 */
class Location
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
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\User", mappedBy="location")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Cluster", mappedBy="location")
     */
    private $cluster;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Precint", mappedBy="location")
     */
    private $precint;

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