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
    //needs to start with ROLE_ to make Symfony recognize it
    const ROLE_USER = 'ROLE_USER';
    const ROLE_PARTNER = 'ROLE_PARTNER';

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
    private $is_partner = false;

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
     * @ORM\OneToMany(targetEntity="Question", mappedBy="createdBy", orphanRemoval=true)
     */
    private $topics;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Post", mappedBy="createdBy", orphanRemoval=true)
     */
    private $posts;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Post", inversedBy="users", orphanRemoval=true)
     */
    private $upvote;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\LearningModule", orphanRemoval=true)
     */
    private $badges;

    /**
     *  @ORM\ManyToMany(targetEntity="App\Entity\Chapter", inversedBy="users")
     */
    private $progress;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Image", mappedBy="user" ,cascade={"persist"}, orphanRemoval=true)
     */
    private $images;

    /**
     * @var ArrayCollection
     */
    private $badgesSorted = null;

    public function __construct()
    {
        $this->topics = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->upvote = new ArrayCollection();
        $this->badges = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->progress = new ArrayCollection();

        $this->created = new \DateTimeImmutable();
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

    public function getRoles() : array
    {
        if ($this->isPartner()) {
            return [self::ROLE_USER, self::ROLE_PARTNER];
        }

        return [self::ROLE_USER];
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function getUsername() : ?string
    {
        return $this->username;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function isPartner(): bool
    {
        return $this->is_partner;
    }

    public function setIsPartner(bool $is_partner): self
    {
        $this->is_partner = $is_partner;

        return $this;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getAvatar():? string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getLanguage(): Language
    {
        return $this->language;
    }

    public function setLanguage(Language $language): self
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
     * @return Collection|Question[]
     */
    public function getTopics(): Collection
    {
        return $this->topics;
    }

    public function addTopic(Question $topic): self
    {
        if (!$this->topics->contains($topic)) {
            $this->topics[] = $topic;
            $topic->setCreatedBy($this);
        }

        return $this;
    }

    public function removeTopic(Question $topic): self
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
        if($this->badgesSorted === null) {
            $this->badgesSorted = new ArrayCollection;

            /** @var LearningModule $badge */
            foreach($this->badges AS $badge) {
                $this->badgesSorted[$badge->getId()] = $badge;
            }
        }

        return $this->badgesSorted;
    }

    public function addBadge(LearningModule $badge): self
    {
        if (!$this->badges->contains($badge)) {
            $this->badges[] = $badge;
            $this->badgesSorted = null;
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
            $this->badgesSorted = null;
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

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setUser($this);
        }

        return $this;
    }

    public function removeProgress(Chapter $progress): self
    {
        if ($this->progress->contains($progress)) {
            $this->progress->removeElement($progress);
        }

        return $this;
    }

    /**
     * @return Collection|Chapter[]
     */
    public function getProgress(): Collection
    {
        return $this->progress;
    }

    /**
     * @return array|Chapter[]
     */
    public function getProgressByLearningModule(LearningModule $learningModule): array
    {
        $progress = [];
        /** @var Chapter $chapter */
        foreach($this->progress AS $chapter) {
            if($chapter->getLearningModule()->getId() === $learningModule->getId()) {
                $progress[$chapter->getId()] = $chapter;
            }
        }
        return $progress;
    }

    public function addProgress(Chapter $progress): self
    {
        if (!$this->progress->contains($progress)) {
            $this->progress[] = $progress;
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getUser() === $this) {
                $image->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @param array $modules
     * @return array|LearningModule[]
     * @throws \Exception
     */
    public function getActiveModules(array $modules) : array
    {
        $list = [];
        foreach($modules AS $learningModule) {
            if(!$learningModule instanceof LearningModule) {
                throw new \Exception('Expected learningModule, got '. get_class($learningModule));
            }

            if(!isset($this->getBadges()[$learningModule->getId()])) {
                $list[] = $learningModule;
            }
        }
        return $list;
    }

    /**
     * @param array $modules
     * @return array|LearningModule[]
     * @throws \Exception
     */
    public function getFinishedModules(array $modules) : array
    {
        $list = [];
        foreach($modules AS $learningModule) {
            if(!$learningModule instanceof LearningModule) {
                throw new \Exception('Expected learningModule, got '. get_class($learningModule));
            }

            if(isset($this->getBadges()[$learningModule->getId()])) {
                $list[] = $learningModule;
            }
        }
        return $list;
    }
}
