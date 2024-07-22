<?php

namespace App\Entity;

use App\Repository\ProjectsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectsRepository::class)]
class Projects
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Nom = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

    /**
     * @var Collection<int, Sources>
     */
    #[ORM\OneToMany(targetEntity: Sources::class, mappedBy: 'project')]
    private Collection $sources;

    /**
     * @var Collection<int, Sources>
     */
    #[ORM\OneToMany(targetEntity: Sources::class, mappedBy: 'project_id')]
    private Collection $source;

    #[ORM\ManyToOne(inversedBy: 'projects')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $user = null;

    #[ORM\Column(length: 255)]
    private ?string $langueoriginale = null;

    #[ORM\Column(length: 255)]
    private ?string $languetraduction1 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $languetraduction2 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $languetraduction3 = null;

    #[ORM\Column(type: "boolean")]
    private $bloque = false;
    

    public function __construct()
    {
        $this->sources = new ArrayCollection();
        $this->source = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): static
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }


    /**
     * @return Collection<int, Sources>
     */
    public function getSources(): Collection
    {
        return $this->sources;
    }

    public function addSource(Sources $source): static
    {
        if (!$this->sources->contains($source)) {
            $this->sources->add($source);
            $source->setProject($this);
        }

        return $this;
    }

    public function removeSource(Sources $source): static
    {
        if ($this->sources->removeElement($source)) {
            // set the owning side to null (unless already changed)
            if ($source->getProject() === $this) {
                $source->setProject(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Sources>
     */
    public function getSource(): Collection
    {
        return $this->source;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getLangueoriginale(): ?string
    {
        return $this->langueoriginale;
    }

    public function setLangueoriginale(string $langueoriginale): static
    {
        $this->langueoriginale = $langueoriginale;

        return $this;
    }

    public function getLanguetraduction1(): ?string
    {
        return $this->languetraduction1;
    }

    public function setLanguetraduction1(string $languetraduction1): static
    {
        $this->languetraduction1 = $languetraduction1;

        return $this;
    }

    public function getLanguetraduction2(): ?string
    {
        return $this->languetraduction2;
    }

    public function setLanguetraduction2(?string $languetraduction2): static
    {
        $this->languetraduction2 = $languetraduction2;

        return $this;
    }

    public function getLanguetraduction3(): ?string
    {
        return $this->languetraduction3;
    }

    public function setLanguetraduction3(?string $languetraduction3): static
    {
        $this->languetraduction3 = $languetraduction3;

        return $this;
    }

    public function isBloque(): bool
    {
        return $this->bloque;
    }

    public function setBloque(bool $bloque): self
    {
        $this->bloque = $bloque;

        return $this;
    }

}
