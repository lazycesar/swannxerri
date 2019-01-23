<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AdminLoginRepository")
 */
class AdminLogin implements UserInterface, \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 4,
     *      max = 100,
     *      minMessage = "Nom trop court {{ limit }}",
     *      maxMessage = "Nom trop long {{ limit }}"
     * )
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $password;

    /**
     * @ORM\Column(type="integer")
     */
    private $level;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creation_date;

    public function getId() : ? int
    {
        return $this->id;
    }

    public function getUsername() : ? string
    {
        return $this->username;
    }

    public function setUsername(string $username) : self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword() : ? string
    {
        return $this->password;
    }

    public function setPassword(string $password) : self
    {
        $this->password = $password;

        return $this;
    }

    public function getLevel() : ? int
    {
        return $this->level;
    }

    public function setLevel(int $level) : self
    {
        $this->level = $level;

        return $this;
    }

    public function getCreationDate() : ? \DateTimeInterface
    {
        return $this->creation_date;
    }

    public function setCreationDate(\DateTimeInterface $creation_date) : self
    {
        $this->creation_date = $creation_date;

        return $this;
    }

    // IMPLEMENTER LES INTERFACES 

    public function getRoles()
    {
        if ($this->level >= 2)
            return ['ROLE_ADMIN', 'ROLE_MEMBRE'];
        elseif ($this->level = 1)
            return ['ROLE_MEMBRE'];
        else
            return [];
    }

    public function eraseCredentials()
    {
    }

    public function getSalt()
    {
            // The bcrypt and argon2i algorithms don't require a separate salt.
            // You *may* need a real salt if you choose a different encoder.
        return null;
    }
    /** @see Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
                // see section on salt below
                // $this->salt,
        ));
    }

    /** @see Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->username,
            $this->password,
                // see section on salt below
                // $this->salt
        ) = unserialize($serialized);
    }
}
