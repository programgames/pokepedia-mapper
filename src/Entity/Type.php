<?php

namespace App\Entity;

use App\Repository\TypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TypeRepository::class)
 */
class Type
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=generation::class, inversedBy="types")
     */
    private $generation;

    /**
     * @ORM\ManyToOne(targetEntity=MoveDamageClass::class, inversedBy="types")
     */
    private $moveDamageClass;

    /**
     * @ORM\OneToMany(targetEntity=PokemonType::class, mappedBy="type")
     */
    private $pokemonTypes;

    /**
     * @ORM\OneToMany(targetEntity=PokemonTypePast::class, mappedBy="type")
     */
    private $pokemonTypePasts;

    public function __construct()
    {
        $this->pokemonTypes = new ArrayCollection();
        $this->pokemonTypePasts = new ArrayCollection();
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

    public function getGeneration(): ?generation
    {
        return $this->generation;
    }

    public function setGeneration(?generation $generation): self
    {
        $this->generation = $generation;

        return $this;
    }

    public function getMoveDamageClass(): ?MoveDamageClass
    {
        return $this->moveDamageClass;
    }

    public function setMoveDamageClass(?MoveDamageClass $moveDamageClass): self
    {
        $this->moveDamageClass = $moveDamageClass;

        return $this;
    }

    /**
     * @return Collection|PokemonType[]
     */
    public function getPokemonTypes(): Collection
    {
        return $this->pokemonTypes;
    }

    public function addPokemonType(PokemonType $pokemonType): self
    {
        if (!$this->pokemonTypes->contains($pokemonType)) {
            $this->pokemonTypes[] = $pokemonType;
            $pokemonType->setType($this);
        }

        return $this;
    }

    public function removePokemonType(PokemonType $pokemonType): self
    {
        if ($this->pokemonTypes->removeElement($pokemonType)) {
            // set the owning side to null (unless already changed)
            if ($pokemonType->getType() === $this) {
                $pokemonType->setType(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PokemonTypePast[]
     */
    public function getPokemonTypePasts(): Collection
    {
        return $this->pokemonTypePasts;
    }

    public function addPokemonTypePast(PokemonTypePast $pokemonTypePast): self
    {
        if (!$this->pokemonTypePasts->contains($pokemonTypePast)) {
            $this->pokemonTypePasts[] = $pokemonTypePast;
            $pokemonTypePast->setType($this);
        }

        return $this;
    }

    public function removePokemonTypePast(PokemonTypePast $pokemonTypePast): self
    {
        if ($this->pokemonTypePasts->removeElement($pokemonTypePast)) {
            // set the owning side to null (unless already changed)
            if ($pokemonTypePast->getType() === $this) {
                $pokemonTypePast->setType(null);
            }
        }

        return $this;
    }
}
