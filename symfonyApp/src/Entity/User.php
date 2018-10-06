<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * User
 *
 * @ORM\Table(name="user", uniqueConstraints={@ORM\UniqueConstraint(name="id_UNIQUE", columns={"id"})})
 * @ORM\Entity
 */
class User implements UserInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=45, nullable=false)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=20, nullable=false)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=20, nullable=false)
     */
    private $lastname;

    /**
     * @var bool
     *
     * @ORM\Column(name="teacher", type="boolean", nullable=false)
     */
    private $teacher;


    public function setUsername(string $username)
    {
        $this->username = $username;
        return $this;
    }
    public function getUsername()
    {
        return $this->username;
    }

    public function setPassword(string $Password)
    {
        $this->password = $Password;
        return $this;
    }
  
    public function getPassword()
    {
        return $this->password;
    }
   
    public function setFirstname(string $Firstname)
    {
        $this->firstname = $Firstname;
        return $this;
    }
    public function getFirstname()
    {
        return $this->firstname;
    }

    public function setLastname(string $Lastname)
    {
        $this->lastname = $Lastname;
        return $this;
    }
    public function getLastname(){
        return $this->lastname;
    }
    public function setId(int $Id)
    {
        $this->idd = $Id;
        return $this;
    }
    public function getId(){
        return $this->id;
    }
    public function setTeacher(bool $Teacher)
    {
        $this->teacher = $Teacher;
        return $this;
    }
    public function getTeacher(){
        return $this->teacher;
    }
    public function getUsers(){
        return $this->firstname;
    }
    public function getRoles()
    {
       return array('ROLE_USER');
    }
    public function getSalt()
    {
        return null;
    }
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }


}
