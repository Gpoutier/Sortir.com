<?php

namespace App\modele;

use App\Entity\Campus;

class FiltreSortie
{

    private ?Campus $campus=null;

    private ?string $nom=null;

    private ?\DateTime $datedebut=null;

    private ?\DateTime $datefin=null;

    private ?bool $inscrit=false;

    private ?bool $organisateur=false;

    /**
     * @return \DateTime|null
     */
    public function getDatedebut(): ?\DateTime
    {
        return $this->datedebut;
    }

    /**
     * @param \DateTime|null $datedebut
     */
    public function setDatedebut(?\DateTime $datedebut): void
    {
        $this->datedebut = $datedebut;
    }

    /**
     * @return \DateTime|null
     */
    public function getDatefin(): ?\DateTime
    {
        return $this->datefin;
    }

    /**
     * @param \DateTime|null $datefin
     */
    public function setDatefin(?\DateTime $datefin): void
    {
        $this->datefin = $datefin;
    }

    /**
     * @return Campus|null
     */
    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    /**
     * @param Campus|null $campus
     */
    public function setCampus(?Campus $campus): void
    {
        $this->campus = $campus;
    }

    /**
     * @return string|null
     */
    public function getNom(): ?string
    {
        return $this->nom;
    }

    /**
     * @param string|null $nom
     */
    public function setNom(?string $nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @return bool|null
     */
    public function getInscrit(): ?bool
    {
        return $this->inscrit;
    }

    /**
     * @param bool|null $inscrit
     */
    public function setInscrit(?bool $inscrit): void
    {
        $this->inscrit = $inscrit;
    }

    /**
     * @return bool|null
     */
    public function getOrganisateur(): ?bool
    {
        return $this->organisateur;
    }

    /**
     * @param bool|null $organisateur
     */
    public function setOrganisateur(?bool $organisateur): void
    {
        $this->organisateur = $organisateur;
    }

}
