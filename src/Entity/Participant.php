<?php

namespace App\Entity;

use App\Repository\ParticipantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Null_;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ParticipantRepository::class)
 * @UniqueEntity(fields={"pseudo"}, message="There is already an account with this pseudo")
 */
class Participant implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $idParticipant;

    /**
     * @Assert\NotBlank(message="Veuillez entrer un nom")
     * @Assert\Length(max=255, maxMessage="Votre nom est trop long.")
     * @ORM\Column(type="string", length=50)
     */
    private $nom;

    /**
     * @Assert\NotBlank(message="Veuillez entrer votre prénom")
     * @Assert\Length(max=255, maxMessage="Votre prénom est trop long.")
     * @ORM\Column(type="string", length=50)
     */
    private $prenom;

    /**
     * @Assert\NotBlank(message="Veuillez entrer votre téléphone")
     * @Assert\Length(max=15, maxMessage="Votre téléphone est trop long.")
     * @ORM\Column(type="string", length=15)
     */
    private $telephone;

    /**
     * @Assert\NotBlank(message="Veuillez entrer votre email")
     * @Assert\Length(max=180, maxMessage="Votre email est trop long.")
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $mail;

    /**
     * @Assert\NotBlank(message="Veuillez entrer votre mot de passe")
     * @Assert\Length(max=180, maxMessage="Votre mot de passe est trop long.")
     * @ORM\Column(type="string", length=255)
     */
    private $motPasse;

    /**
     * @ORM\Column(type="boolean")
     */
    private $administrateur;

    /**
     * @ORM\Column(type="boolean")
     */
    private $actif;

    /**
     * @Assert\NotBlank(message="Veuillez entrer votre pseudo")
     * @Assert\Length(max=180, maxMessage="Votre pseudo est trop long.")
     * @ORM\Column(type="string", length=50, unique=true)
     */
    private $pseudo;

    /**
     * @ORM\ManyToMany(targetEntity=Sortie::class, mappedBy="participants")
     */
    private $sorties;

    /**
     * @ORM\OneToMany(targetEntity=Sortie::class, mappedBy="organisateur")
     */
    private $sortieOrga;

    /**
     * @ORM\ManyToOne(targetEntity=Campus::class, inversedBy="participants")
     * @ORM\JoinColumn(referencedColumnName="id_campus", nullable=false)
     */
    private $campus;

    public function __construct()
    {
        $this->sorties = new ArrayCollection();
        $this->sortieOrga = new ArrayCollection();
        $this ->actif = false;
        $this ->administrateur =false;
    }

    public function getIdParticipant(): ?int
    {
        return $this->idParticipant;
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

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getMotPasse(): ?string
    {
        return $this->motPasse;
    }

    public function setMotPasse(string $motPasse): self
    {
        $this->motPasse = $motPasse;

        return $this;
    }

    public function getAdministrateur(): ?bool
    {
        return $this->administrateur;
    }

    public function setAdministrateur(bool $administrateur): self
    {
        $this->administrateur = $administrateur;

        return $this;
    }

    public function getActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): self
    {
        $this->actif = $actif;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * @return Collection<int, Sortie>
     */
    public function getSorties(): Collection
    {
        return $this->sorties;
    }

    public function addSorty(Sortie $sorty): self
    {
        if (!$this->sorties->contains($sorty)) {
            $this->sorties[] = $sorty;
            $sorty->addParticipant($this);
        }

        return $this;
    }

    public function removeSorty(Sortie $sorty): self
    {
        if ($this->sorties->removeElement($sorty)) {
            $sorty->removeParticipant($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Sortie>
     */
    public function getSortieOrga(): Collection
    {
        return $this->sortieOrga;
    }

    public function addSortieOrga(Sortie $sortieOrga): self
    {
        if (!$this->sortieOrga->contains($sortieOrga)) {
            $this->sortieOrga[] = $sortieOrga;
            $sortieOrga->setOrganisateur($this);
        }

        return $this;
    }

    public function removeSortieOrga(Sortie $sortieOrga): self
    {
        $this->sortieOrga->removeElement($sortieOrga);

        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): self
    {
        $this->campus = $campus;

        return $this;
    }

    public function getRoles() : array
    {

        return $this -> administrateur ? ["ROLE_ADMIN"] : ["ROLE_USER"];
    }

    public function getPassword() : string
    {

        return $this -> motPasse;
    }

    public function getSalt() : ?string
    {
       return null;
    }

    public function eraseCredentials() : void
    {

    }

    public function getUsername() : string
    {
        return $this -> pseudo;
    }

    public function getUserIdentifier() : string
    {

        return $this->mail;
    }
}
