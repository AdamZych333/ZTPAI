<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\MemeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=MemeRepository::class)
 * @ApiResource(
 *     collectionOperations={"get"={"normalization_context"={"groups"="meme:list"}}},
 *     itemOperations={"get"={"normalization_context"={"groups"="meme:item"}}},
 *     order={"createdAt"="DESC"},
 *     paginationEnabled=false
 * )
 */
class Meme
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @Groups({"meme:list", "meme:item"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     *
     * @Groups({"meme:list", "meme:item"})
     */
    private $title;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank
     *
     * @Groups({"meme:list", "meme:item"})
     */
    private $likes;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank
     *
     * @Groups({"meme:list", "meme:item"})
     */
    private $dislikes;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank
     *
     * @Groups({"meme:list", "meme:item"})
     */
    private $created_at;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     *
     * @Groups({"meme:list", "meme:item"})
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank
     *
     * @Groups({"meme:list", "meme:item"})
     */
    private $created_by;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="meme", orphanRemoval=true)
     *
     * @Groups({"meme:list", "meme:item"})
     */
    private $comments;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $slug;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getLikes(): ?int
    {
        return $this->likes;
    }

    public function setLikes(int $likes): self
    {
        $this->likes = $likes;

        return $this;
    }

    public function getDislikes(): ?int
    {
        return $this->dislikes;
    }

    public function setDislikes(int $dislikes): self
    {
        $this->dislikes = $dislikes;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function setCreatedBy(?User $created_by): self
    {
        $this->created_by = $created_by;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->created_by;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setMeme($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getMeme() === $this) {
                $comment->setMeme(null);
            }
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function computeSlug(SluggerInterface $slugger){
        if(!$this->slug || '-' === $this->slug){
            $this->slug = (string) $slugger->slug((string) $this)->lower();
        }
    }

    public function __toString(): string{
        return $this->title;
    }
}
