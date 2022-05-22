<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 *  * @UniqueEntity(fields={"name"}, message="Ce nom de catégorie est déjà utilisé")
 */
class Category
{
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
     * @ORM\OneToMany(targetEntity=Figure::class, mappedBy="category")
     */
    private $figure;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    public function __construct()
    {
        $this->figure = new ArrayCollection();
        $this->setCreatedAt(new \DateTimeImmutable());
        $this->setUpdatedAt(new \DateTimeImmutable());
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

    /**
     * @return Collection<int, Figure>
     */
    public function getFigure(): Collection
    {
        return $this->figure;
    }

    public function addFigure(Figure $figure): self
    {
        if (!$this->figure->contains($figure)) {
            $this->figure[] = $figure;
            $figure->setCategory($this);
        }

        return $this;
    }

    public function removeFigure(Figure $figure): self
    {
        if ($this->figure->removeElement($figure)) {
            // set the owning side to null (unless already changed)
            if ($figure->getCategory() === $this) {
                $figure->setCategory(null);
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
}
