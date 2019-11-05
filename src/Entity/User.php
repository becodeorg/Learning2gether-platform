<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
{
    const ROLE_USER = 'ROLE_USER';
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name = '';

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email = '';

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password = '';



    /**
     * @ORM\Column(type="boolean")
     */
    private $is_partner = 0;//TODO default value of is_partner should be 0

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $avatar;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Language")
     * @ORM\JoinColumn(nullable=false)
     */
    private $language;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**

     * @ORM\OneToMany(targetEntity="App\Entity\Topic", mappedBy="createdBy", orphanRemoval=true)
     */
    private $topics;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Post", mappedBy="createdBy", orphanRemoval=true)
     */
    private $posts;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Post", inversedBy="users")
     */
    private $upvote;

   
      
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\LearningModule", inversedBy="users")
     */
    private $badges;

    public function __construct()
    {   $this->topics = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->upvote = new ArrayCollection();
        $this->badges = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setPassword($password): void
    {
        $this->password = $password;
    }

    public function getRoles()
    {
        return [self::ROLE_USER];
        // TODO: Implement getRoles() method.
    }

    public function getPassword() : string
    {
        return $this->password;
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getPasswordHash(): ?string
    {
        return $this->password_hash;
    }

    public function setPasswordHash(string $password_hash): self
    {
        $this->password_hash = $password_hash;

        return $this;
    }

    public function getIsPartner(): ?bool
    {
        return $this->is_partner;
    }

    public function setIsPartner(bool $is_partner): self
    {
        $this->is_partner = $is_partner;

        return $this;
    }

    public function getBadgrKey(): ?string
    {
        return $this->badgr_key;
    }

    public function setBadgrKey(string $badgr_key): self
    {
        $this->badgr_key = $badgr_key;

        return $this;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getLanguage(): ?Language
    {
        return $this->language;
    }

    public function setLanguage(?Language $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    /**
     * @return Collection|Topic[]
     */
    public function getTopics(): Collection
    {
        return $this->topics;
    }

    public function addTopic(Topic $topic): self
    {
        if (!$this->topics->contains($topic)) {
            $this->topics[] = $topic;
            $topic->setCreatedBy($this);
        }

        return $this;
    }

    public function removeTopic(Topic $topic): self
    {
        if ($this->topics->contains($topic)) {
            $this->topics->removeElement($topic);
            // set the owning side to null (unless already changed)
            if ($topic->getCreatedBy() === $this) {
                $topic->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Post[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setCreatedBy($this);
        }
    }
    /**
     * @return Collection|LearningModule[]
     */
    public function getBadges(): Collection
    {
        return $this->badges;
    }

    public function addBadge(LearningModule $badge): self
    {
        if (!$this->badges->contains($badge)) {
            $this->badges[] = $badge;

        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->contains($post)) {
            $this->posts->removeElement($post);
            // set the owning side to null (unless already changed)
            if ($post->getCreatedBy() === $this) {
                $post->setCreatedBy(null);
            }
        }
    }
    public function removeBadge(LearningModule $badge): self
    {
        if ($this->badges->contains($badge)) {
            $this->badges->removeElement($badge);

        }

        return $this;
    }


    /**
     * @return Collection|Post[]
     */
    public function getUpvote(): Collection
    {
        return $this->upvote;
    }

    public function addUpvote(Post $upvote): self
    {
        if (!$this->upvote->contains($upvote)) {
            $this->upvote[] = $upvote;
        }

        return $this;
    }

    public function removeUpvote(Post $upvote): self
    {
        if ($this->upvote->contains($upvote)) {
            $this->upvote->removeElement($upvote);
        }

        return $this;
    }
}
