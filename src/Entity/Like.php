<?php

namespace App\Entity;

use App\Repository\LikeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LikeRepository::class)
 * @ORM\Table(name="`like`")
 */
class Like
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Meme::class, inversedBy="likes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $meme;

    /**
     * @ORM\ManyToOne(targetEntity=user::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $from_user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMeme(): ?Meme
    {
        return $this->meme;
    }

    public function setMeme(?Meme $meme): self
    {
        $this->meme = $meme;

        return $this;
    }

    public function getFromUser(): ?user
    {
        return $this->from_user;
    }

    public function setFromUser(?user $from_user): self
    {
        $this->from_user = $from_user;

        return $this;
    }

    public function __toString(): string{
        return $this->meme->getTitle().":".$this->id;
    }
}
