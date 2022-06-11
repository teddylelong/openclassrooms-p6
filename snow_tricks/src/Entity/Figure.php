<?php

namespace App\Entity;

use App\Repository\FigureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=FigureRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields={"name"}, message="Ce nom de figure est déjà utilisé")
 */
class Figure
{
    const STATUS_PENDING = 'status.pending';
    const STATUS_ACCEPTED = 'status.accepted';
    const STATUS_REJECTED = 'status.rejected';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=45, unique=true)
     * @Assert\Length(
     * min = 2,
     * max = 45,
     * minMessage = "Le nom doit faire au moins {{ limit }} caractères.",
     * maxMessage = "Le nom ne peut pas dépasser {{ limit }} caractères."
     * )
     * @Assert\NotNull()
     * @Assert\NotBlank()
     * @Assert\Type("string")
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotNull()
     * @Assert\NotBlank()
     * @Assert\Type("string")
     */
    private $description;

    /**
     * @ORM\Column(type="datetime_immutable", options={"default": "CURRENT_TIMESTAMP"})
     * @Assert\NotNull()
     * @Assert\NotBlank()
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime_immutable", options={"default": "CURRENT_TIMESTAMP"})
     * @Assert\NotNull()
     * @Assert\NotBlank()
     */
    private $updated_at;

    /**
     * @ORM\Column(
     *     type="string",
     *     columnDefinition="ENUM('status.pending', 'status.accepted', 'status.rejected')",
     *     options={"default": "status.pending"},
     *     nullable=false
     * )
     * @Assert\Type("string")
     * @Assert\NotBlank()
     * @Assert\NotNull()
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="figures")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Type("App\Entity\User")
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity=FigureMedias::class, mappedBy="figure", cascade={"persist"}, orphanRemoval=true)
     */
    private $figureMedias;

    /**
     * @ORM\OneToMany(targetEntity=FigureImages::class, mappedBy="figure", cascade={"persist"}, orphanRemoval=true)
     */
    private $figureImages;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="figure")
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="figure", cascade={"persist"}, orphanRemoval=true)
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $comments;

    public function __construct()
    {
        $this->setStatus(self::STATUS_PENDING);
        $this->setCreatedAt(new \DateTimeImmutable());
        $this->setUpdatedAt(new \DateTimeImmutable());
        $this->figureMedias = new ArrayCollection();
        $this->figureImages = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function computeSlug(SluggerInterface $slugger)
    {
        if (!$this->getSlug() || '-' === $this->getSlug()) {
            $this->setSlug($slugger->slug($this->getName())->lower());
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user_id): self
    {
        $this->user = $user_id;

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

    /**
     * @return Collection<int, FigureMedias>
     */
    public function getFigureMedias(): Collection
    {
        return $this->figureMedias;
    }

    public function addFigureMedia(FigureMedias $figureMedia): self
    {
        if (!$this->figureMedias->contains($figureMedia)) {
            $this->figureMedias[] = $figureMedia;
            $figureMedia->setFigure($this);
        }

        return $this;
    }

    public function removeFigureMedia(FigureMedias $figureMedia): self
    {
        if ($this->figureMedias->removeElement($figureMedia)) {
            // set the owning side to null (unless already changed)
            if ($figureMedia->getFigure() === $this) {
                $figureMedia->setFigure(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, FigureImages>
     */
    public function getFigureImages(): Collection
    {
        return $this->figureImages;
    }

    public function addFigureImage(FigureImages $figureImage): self
    {
        if (!$this->figureImages->contains($figureImage)) {
            $this->figureImages[] = $figureImage;
            $figureImage->setFigure($this);
        }

        return $this;
    }

    public function removeFigureImage(FigureImages $figureImage): self
    {
        if ($this->figureImages->removeElement($figureImage)) {
            // set the owning side to null (unless already changed)
            if ($figureImage->getFigure() === $this) {
                $figureImage->setFigure(null);
            }
        }

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setFigure($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getFigure() === $this) {
                $comment->setFigure(null);
            }
        }

        return $this;
    }
}
