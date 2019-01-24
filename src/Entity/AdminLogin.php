<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AdminLoginRepository")
 * @UniqueEntity("email", message = "Un compte a déjà été enregistré avec cette adresse email.")
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
     *      min = 6,
     *      max = 100,
     *      minMessage = "Votre nom d'utilisateur doit comporter au moins 6 caractères. {{ limit }}",
     *      maxMessage = "Votre nom d'utilisateur ne peut comporter plus de 100 caractères. {{ limit }}"
     * )
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 8,
     *      max = 100,
     *      minMessage = "Votre mot de passe doit comporter au moins 8 caractères.",
     *      maxMessage = "Votre mot de passe ne peut comporter plus de 100 caractères."
     * )
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

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $profil;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cle_activation;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_limite_activation;

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

    public function getPrenom() : ? string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom) : self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNom() : ? string
    {
        return $this->nom;
    }

    public function setNom(string $nom) : self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getEmail() : ? string
    {
        return $this->email;
    }

    public function setEmail(string $email) : self
    {
        $this->email = $email;

        return $this;
    }

    public function getProfil() : ? string
    {
        return $this->profil;
    }

    public function setProfil(string $profil) : self
    {
        $this->profil = $profil;

        return $this;
    }

    public function getCleActivation() : ? string
    {
        return $this->cle_activation;
    }

    public function setCleActivation(string $cle_activation) : self
    {
        $this->cle_activation = $cle_activation;

        return $this;
    }

    public function getDateLimiteActivation() : ? \DateTimeInterface
    {
        return $this->date_limite_activation;
    }

    public function setDateLimiteActivation(\DateTimeInterface $date_limite_activation) : self
    {
        $this->date_limite_activation = $date_limite_activation;

        return $this;
    }
}
