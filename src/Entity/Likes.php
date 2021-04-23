<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\LikesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=LikesRepository::class)
 * @ApiResource(
 *     collectionOperations={"get"={"normalization_context"={"groups"="likes:list"}}},
 *     itemOperations={"get"={"normalization_context"={"groups"="likes:item"}}},
 *     paginationEnabled=false
 * )
 */
class Likes
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @Groups({"likes:list", "likes:item"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank
     *
     * @Groups({"likes:list", "likes:item"})
     */
    private $from_user;

    /**
     * @ORM\ManyToOne(targetEntity=Meme::class)
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank
     *
     * @Groups({"likes:list", "likes:item"})
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
