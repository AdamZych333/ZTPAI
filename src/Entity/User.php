<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="`user`")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 * @ApiResource(
 *     collectionOperations={"get"={"normalization_context"={"groups"="user:list"}}},
 *     itemOperations={"get"={"normalization_context"={"groups"="user:item"}}},
 *     order={"createdAt"="DESC"},
 *     paginationEnabled=false
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @Groups({"user:list", "user:item"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Email
     * @Assert\NotBlank
     *
     * @Groups({"user:list", "user:item"})
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     *
     * @Groups({"user:list", "user:item"})
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     *
     * @Groups({"user:list", "user:item"})
     */
    private $password;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Groups({"user:list", "user:item"})
     */
    private $joinedAt;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Groups({"user:list", "user:item"})
     */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity=Like::class, mappedBy="from_user", orphanRemoval=true)
     */
    private $likes;

    /**
     * @ORM\OneToMany(targetEntity=Dislike::class, mappedBy="from_user", orphanRemoval=true)
     */
    private $dislikes;

    public function __construct()
    {
        $this->likes = new ArrayCollection();
        $this->dislikes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function __toString(): string{
        return $this->email;
    }

    public function getJoinedAt(): ?\DateTimeInterface
    {
        return $this->joinedAt;
    }

    public function setJoinedAt(\DateTimeInterface $joinedAt): self
    {
        $this->joinedAt = $joinedAt;

        return $this;
    }

    /**
     * @return Collection|Like[]
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Like $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes[] = $like;
            $like->setFromUser($this);
        }

        return $this;
    }

    public function removeLike(Like $like): self
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getFromUser() === $this) {
                $like->setFromUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Dislike[]
     */
    public function getDislikes(): Collection
    {
        return $this->dislikes;
    }

    public function addDislike(Dislike $dislike): self
    {
        if (!$this->dislikes->contains($dislike)) {
            $this->dislikes[] = $dislike;
            $dislike->setFromUser($this);
        }

        return $this;
    }

    public function removeDislike(Dislike $dislike): self
    {
        if ($this->dislikes->removeElement($dislike)) {
            // set the owning side to null (unless already changed)
            if ($dislike->getFromUser() === $this) {
                $dislike->setFromUser(null);
            }
        }

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function setJoinedAtValue(){
        $this->joinedAt = new \DateTime();
    }

    /**
     * @ORM\PrePersist
     */
    public function setRolesValue(){
        $this->roles = ['ROLE_USER'];
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
}
