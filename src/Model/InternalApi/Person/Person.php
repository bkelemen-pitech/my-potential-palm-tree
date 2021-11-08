<?php

declare(strict_types=1);

namespace App\Model\InternalApi\Person;

use Symfony\Component\Serializer\Annotation\SerializedName;

class Person
{
    protected int $personneId;
    protected ?int $userId = null;
    protected ?int $personneTypeId = null;
    protected ?string $nom = null;
    protected ?string $prenom = null;
    protected ?string $email = null;
    protected ?string $adresse = null;
    protected ?string $telephone = null;
    protected bool $newsletter = false;
    protected ?string $creation = null;
    protected ?string $modification = null;
    protected string $personneProfilId = '1';
    protected bool $actif = true;
    protected ?int $salaire = null;
    protected int $label = 0;
    protected ?int $salaireDeclare = null;
    protected ?int $salaireExtrait = null;
    protected bool $visible = true;
    protected ?int $userDossierId = null;
    protected ?string $dateNaissance = null;
    protected ?int $etatcivil = null;
    protected ?string $nbAdultes = null;
    protected ?string $nbEnfants = null;
    protected ?string $nbAnimaux = null;
    protected ?string $loyerActuel = null;
    protected ?string $iban = null;
    protected ?string $bic = null;
    protected ?string $lang = null;
    protected string $statutVerification = '0';
    protected ?string $attribut1 = null;
    protected ?string $attribut2 = null;
    protected ?string $attribut3 = null;
    protected ?string $peps = null;
    protected ?string $lastPepDate = null;
    protected int $risque = 0;
    protected ?string $kybSocieteType = null;
    protected string $isKybRepresentantLegal = '0';
    protected string $statutVerification2 = '0';
    protected ?string $personneUid = null;
    protected ?string $nationalite = null;
    protected string $pepMax = '0';
    protected string $sanctionMax = '0';
    protected ?string $personneProfil = null;
    protected bool $telephoneValide = false;
    protected bool $clientBnpp = false;
    protected ?string $screeningActif = null;
    protected ?string $worldcheckCaseSystemId = null;
    protected ?int $pendingScreeningCreatedAt = null;
    protected ?string $partenairePersonneId = null;
    protected ?int $npai = null;

    /**
     * @var PersonInfo[]
     * @SerializedName("person_info")
     */
    protected array $personInfos = [];

    public function getPersonneId(): int
    {
        return $this->personneId;
    }

    public function setPersonneId(int $personneId): Person
    {
        $this->personneId = $personneId;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(?int $userId): Person
    {
        $this->userId = $userId;

        return $this;
    }

    public function getPersonneTypeId(): ?int
    {
        return $this->personneTypeId;
    }

    public function setPersonneTypeId(?int $personneTypeId): Person
    {
        $this->personneTypeId = $personneTypeId;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): Person
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): Person
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): Person
    {
        $this->email = $email;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): Person
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): Person
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function isNewsletter(): bool
    {
        return $this->newsletter;
    }

    public function setNewsletter(bool $newsletter): Person
    {
        $this->newsletter = $newsletter;

        return $this;
    }

    public function getCreation(): ?string
    {
        return $this->creation;
    }

    public function setCreation(?string $creation): Person
    {
        $this->creation = $creation;

        return $this;
    }

    public function getModification(): ?string
    {
        return $this->modification;
    }

    public function setModification(?string $modification): Person
    {
        $this->modification = $modification;

        return $this;
    }

    public function getPersonneProfilId(): string
    {
        return $this->personneProfilId;
    }

    public function setPersonneProfilId(string $personneProfilId): Person
    {
        $this->personneProfilId = $personneProfilId;

        return $this;
    }

    public function isActif(): bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): Person
    {
        $this->actif = $actif;

        return $this;
    }

    public function getSalaire(): ?int
    {
        return $this->salaire;
    }

    public function setSalaire(?int $salaire): Person
    {
        $this->salaire = $salaire;

        return $this;
    }

    public function getLabel(): int
    {
        return $this->label;
    }

    public function setLabel(int $label): Person
    {
        $this->label = $label;

        return $this;
    }

    public function getSalaireDeclare(): ?int
    {
        return $this->salaireDeclare;
    }

    public function setSalaireDeclare(?int $salaireDeclare): Person
    {
        $this->salaireDeclare = $salaireDeclare;

        return $this;
    }

    public function getSalaireExtrait(): ?int
    {
        return $this->salaireExtrait;
    }

    public function setSalaireExtrait(?int $salaireExtrait): Person
    {
        $this->salaireExtrait = $salaireExtrait;

        return $this;
    }

    public function isVisible(): bool
    {
        return $this->visible;
    }

    public function setVisible(bool $visible): Person
    {
        $this->visible = $visible;

        return $this;
    }

    public function getUserDossierId(): ?int
    {
        return $this->userDossierId;
    }

    public function setUserDossierId(?int $userDossierId): Person
    {
        $this->userDossierId = $userDossierId;

        return $this;
    }

    public function getDateNaissance(): ?string
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(?string $dateNaissance): Person
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    public function getEtatcivil(): ?int
    {
        return $this->etatcivil;
    }

    public function setEtatcivil(?int $etatcivil): Person
    {
        $this->etatcivil = $etatcivil;

        return $this;
    }

    public function getNbAdultes(): ?string
    {
        return $this->nbAdultes;
    }

    public function setNbAdultes(?string $nbAdultes): Person
    {
        $this->nbAdultes = $nbAdultes;

        return $this;
    }

    public function getNbEnfants(): ?string
    {
        return $this->nbEnfants;
    }

    public function setNbEnfants(?string $nbEnfants): Person
    {
        $this->nbEnfants = $nbEnfants;

        return $this;
    }

    public function getNbAnimaux(): ?string
    {
        return $this->nbAnimaux;
    }

    public function setNbAnimaux(?string $nbAnimaux): Person
    {
        $this->nbAnimaux = $nbAnimaux;

        return $this;
    }

    public function getLoyerActuel(): ?string
    {
        return $this->loyerActuel;
    }

    public function setLoyerActuel(?string $loyerActuel): Person
    {
        $this->loyerActuel = $loyerActuel;

        return $this;
    }

    public function getIban(): ?string
    {
        return $this->iban;
    }

    public function setIban(?string $iban): Person
    {
        $this->iban = $iban;

        return $this;
    }

    public function getBic(): ?string
    {
        return $this->bic;
    }

    public function setBic(?string $bic): Person
    {
        $this->bic = $bic;

        return $this;
    }

    public function getLang(): ?string
    {
        return $this->lang;
    }

    public function setLang(?string $lang): Person
    {
        $this->lang = $lang;

        return $this;
    }

    public function getStatutVerification(): string
    {
        return $this->statutVerification;
    }

    public function setStatutVerification(string $statutVerification): Person
    {
        $this->statutVerification = $statutVerification;

        return $this;
    }

    public function getAttribut1(): ?string
    {
        return $this->attribut1;
    }

    public function setAttribut1(?string $attribut1): Person
    {
        $this->attribut1 = $attribut1;

        return $this;
    }

    public function getAttribut2(): ?string
    {
        return $this->attribut2;
    }

    public function setAttribut2(?string $attribut2): Person
    {
        $this->attribut2 = $attribut2;

        return $this;
    }

    public function getAttribut3(): ?string
    {
        return $this->attribut3;
    }

    public function setAttribut3(?string $attribut3): Person
    {
        $this->attribut3 = $attribut3;

        return $this;
    }

    public function getPeps(): ?string
    {
        return $this->peps;
    }

    public function setPeps(?string $peps): Person
    {
        $this->peps = $peps;

        return $this;
    }

    public function getLastPepDate(): ?string
    {
        return $this->lastPepDate;
    }

    public function setLastPepDate(?string $lastPepDate): Person
    {
        $this->lastPepDate = $lastPepDate;

        return $this;
    }

    public function getRisque(): int
    {
        return $this->risque;
    }

    public function setRisque(int $risque): Person
    {
        $this->risque = $risque;

        return $this;
    }

    public function getKybSocieteType(): ?string
    {
        return $this->kybSocieteType;
    }

    public function setKybSocieteType(?string $kybSocieteType): Person
    {
        $this->kybSocieteType = $kybSocieteType;

        return $this;
    }

    public function getIsKybRepresentantLegal(): string
    {
        return $this->isKybRepresentantLegal;
    }

    public function setIsKybRepresentantLegal(string $isKybRepresentantLegal): Person
    {
        $this->isKybRepresentantLegal = $isKybRepresentantLegal;

        return $this;
    }
    function getStatutVerification2(): string
    {
        return $this->statutVerification2;
    }

    public function setStatutVerification2(string $statutVerification2): Person
    {
        $this->statutVerification2 = $statutVerification2;

        return $this;
    }

    public function getPersonneUid(): ?string
    {
        return $this->personneUid;
    }

    public function setPersonneUid(?string $personneUid): Person
    {
        $this->personneUid = $personneUid;

        return $this;
    }

    public function getNationalite(): ?string
    {
        return $this->nationalite;
    }

    public function setNationalite(?string $nationalite): Person
    {
        $this->nationalite = $nationalite;

        return $this;
    }

    public function getPepMax(): string
    {
        return $this->pepMax;
    }

    public function setPepMax(string $pepMax): Person
    {
        $this->pepMax = $pepMax;

        return $this;
    }

    public function getSanctionMax(): string
    {
        return $this->sanctionMax;
    }

    public function setSanctionMax(string $sanctionMax): Person
    {
        $this->sanctionMax = $sanctionMax;

        return $this;
    }

    public function getPersonneProfil(): ?string
    {
        return $this->personneProfil;
    }

    public function setPersonneProfil(?string $personneProfil): Person
    {
        $this->personneProfil = $personneProfil;

        return $this;
    }

    public function isTelephoneValide(): bool
    {
        return $this->telephoneValide;
    }

    public function setTelephoneValide(bool $telephoneValide): Person
    {
        $this->telephoneValide = $telephoneValide;

        return $this;
    }

    public function isClientBnpp(): bool
    {
        return $this->clientBnpp;
    }

    public function setClientBnpp(bool $clientBnpp): Person
    {
        $this->clientBnpp = $clientBnpp;

        return $this;
    }

    public function getScreeningActif(): ?string
    {
        return $this->screeningActif;
    }

    public function setScreeningActif(?string $screeningActif): Person
    {
        $this->screeningActif = $screeningActif;

        return $this;
    }

    public function getWorldcheckCaseSystemId(): ?string
    {
        return $this->worldcheckCaseSystemId;
    }

    public function setWorldcheckCaseSystemId(?string $worldcheckCaseSystemId): Person
    {
        $this->worldcheckCaseSystemId = $worldcheckCaseSystemId;

        return $this;
    }

    public function getPendingScreeningCreatedAt(): ?int
    {
        return $this->pendingScreeningCreatedAt;
    }

    public function setPendingScreeningCreatedAt(?int $pendingScreeningCreatedAt): Person
    {
        $this->pendingScreeningCreatedAt = $pendingScreeningCreatedAt;

        return $this;
    }

    public function getPartenairePersonneId(): ?string
    {
        return $this->partenairePersonneId;
    }

    public function setPartenairePersonneId(?string $partenairePersonneId): Person
    {
        $this->partenairePersonneId = $partenairePersonneId;

        return $this;
    }

    public function getNpai(): ?int
    {
        return $this->npai;
    }

    public function setNpai(?int $npai): Person
    {
        $this->npai = $npai;

        return $this;
    }

    /**
     * @return PersonInfo[]
     */
    public function getPersonInfos(): array
    {
        return $this->personInfos;
    }

    /**
     * @var PersonInfo[] $personInfos
     */
    public function setPersonInfos(array $personInfos): Person
    {
        $this->personInfos = $personInfos;

        return $this;
    }
}
