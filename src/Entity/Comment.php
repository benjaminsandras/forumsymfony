<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 */
class Comment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $textec;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Comment", inversedBy="comments")
     */
    private $fk_comment;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="fk_comment")
     */
    private $comments;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Article")
     */
    private $fk_article;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTextec(): ?string
    {
        return $this->textec;
    }

    public function setTextec(string $textec): self
    {
        $this->textec = $textec;

        return $this;
    }

    public function getFkComment(): ?self
    {
        return $this->fk_comment;
    }

    public function setFkComment(?self $fk_comment): self
    {
        $this->fk_comment = $fk_comment;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(self $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setFkComment($this);
        }

        return $this;
    }

    public function removeComment(self $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getFkComment() === $this) {
                $comment->setFkComment(null);
            }
        }

        return $this;
    }

    public function getFkArticle(): ?Article
    {
        return $this->fk_article;
    }

    public function setFkArticle(?Article $fk_article): self
    {
        $this->fk_article = $fk_article;

        return $this;
    }

    
}
