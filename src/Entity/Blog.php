<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BlogRepository")
 */
class Blog
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $publicationDate;

    public $uploadImage;

    public function getId() : ? int
    {
        return $this->id;
    }

    public function getTitle() : ? string
    {
        return $this->title;
    }

    public function setTitle(string $title) : self
    {
        $this->title = $title;

        return $this;
    }

    public function getImage() : ? string
    {
        return $this->image;
    }

    public function setImage(string $image) : self
    {
        $this->image = $image;

        return $this;
    }

    public function getContent() : ? string
    {
        return $this->content;
    }

    public function setContent(string $content) : self
    {
        $this->content = $content;

        return $this;
    }

    public function getPublicationDate() : ? \DateTimeInterface
    {
        return $this->publicationDate;
    }

    public function setPublicationDate(\DateTimeInterface $publicationDate) : self
    {
        $this->publicationDate = $publicationDate;

        return $this;
    }
}
