<?php

namespace App\Entity;

use App\Repository\DislikesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DislikesRepository::class)
 */
class Dislikes
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=user::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $from_user;

    /**
     * @ORM\ManyToOne(targetEntity=meme::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $meme;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMeme(): ?meme
    {
        return $this->meme;
    }

    public function setMeme(?meme $meme): self
    {
        $this->meme = $meme;

        return $this;
    }
}
