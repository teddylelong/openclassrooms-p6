<?php

namespace App\Entity;

use App\Repository\FigureMediasRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=FigureMediasRepository::class)
 */
class FigureMedias
{
    public const VIDEO_TYPE = 'video';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull()
     * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern="/^<iframe[^>]*>\s*<\/iframe>/",
     *     message="Le code saisi n'est pas valide. Copiez/collez le code d'une vidéo embarquée, ce code doit commencer par <iframe>"
     * )
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="datetime_immutable", options={"default": "CURRENT_TIMESTAMP"})
     */
    private $created_at;

    /**
     * @ORM\ManyToOne(targetEntity=Figure::class, inversedBy="figureMedias")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $figure;

    public function __construct()
    {
        $this->setType(self::VIDEO_TYPE);
        $this->setCreatedAt(new \DateTimeImmutable());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

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

    public function getFigure(): ?Figure
    {
        return $this->figure;
    }

    public function setFigure(?Figure $figure): self
    {
        $this->figure = $figure;

        return $this;
    }
}
