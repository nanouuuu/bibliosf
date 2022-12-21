<?php

namespace App\Entity;

use App\Repository\AuteurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AuteurRepository::class)]
class Auteur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    private ?string $nom = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $prenom = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $biographie = null;

    #[ORM\OneToMany(mappedBy: 'auteur', targetEntity: Livre::class, orphanRemoval: true)]
    private Collection $livres;

    public function __construct()
    {
        $this->livres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getBiographie(): ?string
    {
        return $this->biographie;
    }

    public function setBiographie(?string $biographie): self
    {
        $this->biographie = $biographie;

        return $this;
    }

    /**
     * @return Collection<int, Livre>
     */
    public function getLivres(): Collection
    {
        return $this->livres;
    }

    public function addLivre(Livre $livre): self
    {
        if (!$this->livres->contains($livre)) {
            $this->livres->add($livre);
            $livre->setAuteur($this);
        }

        return $this;
    }

    public function removeLivre(Livre $livre): self
    {
        if ($this->livres->removeElement($livre)) {
            // set the owning side to null (unless already changed)
            if ($livre->getAuteur() === $this) {
                $livre->setAuteur(null);
            }
        }

        return $this;
    }

    public function getIdentite()
    {
        /**
        Opérateur ??
                vérifie si la valeur avant l'opérateur existe et n'est pas null, sinon renvoie la valeur après l'opérateur
        Opérateur ?:
                vérifie si  la valeur avant n'est pas vide sinon renvoie la valeur après l'opérateur
                ⚠ la variable avant l'opérateur doit exister sinon une erreur est déclenchée

            ex : $toto = $test ?? "la variable est nulle";
            ex : $tata = $test ?; "la variable est vide";
        */
        $identite = $this->prenom ?: "";

        $identite .= ($this->prenom ? " " : "") . $this->nom;
        return $identite;
    }
}
