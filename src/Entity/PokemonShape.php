<?php

namespace App\Entity;

use App\Repository\PokemonShapeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PokemonShapeRepository::class)
 */
class PokemonShape
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
     * @ORM\OneToMany(targetEntity=PokemonSpecy::class, mappedBy="pokemonShape")
     */
    private $pokemonSpecies;

    public function __construct()
    {
        $this->pokemonSpecies = new ArrayCollection();
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

    /**
     * @return Collection|PokemonSpecy[]
     */
    public function getPokemonSpecies(): Collection
    {
        return $this->pokemonSpecies;
    }

    public function addPokemonSpecies(PokemonSpecy $pokemonSpecies): self
    {
        if (!$this->pokemonSpecies->contains($pokemonSpecies)) {
            $this->pokemonSpecies[] = $pokemonSpecies;
            $pokemonSpecies->setPokemonShape($this);
        }

        return $this;
    }

    public function removePokemonSpecies(PokemonSpecy $pokemonSpecies): self
    {
        if ($this->pokemonSpecies->removeElement($pokemonSpecies)) {
            // set the owning side to null (unless already changed)
            if ($pokemonSpecies->getPokemonShape() === $this) {
                $pokemonSpecies->setPokemonShape(null);
            }
        }

        return $this;
    }
}
