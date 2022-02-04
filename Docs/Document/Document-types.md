### Document types API
This API retrieves the document types for a person type. 
Internally it will call `internalAPI/documents/types`. 

__Method__: GET.  
__URL__: `/api/v1/documents/types`.  
__Query params__:
- __person_type_id__ (int)   
- __agency_id__ (int)

Request example:

```http request
GET {HOST_NAME}/api/v1/document/types?person_type_id=2&agency_id=1
Accept: application/json 
Content-Type: application/json 

200 OK
{
  "types":{[
    { 
      "document_type_id": 1,
      "sub_document_type_id": 1
      "treatment_instruction": "Consigne de traitement carte Nationale d’Identité"
    },
    { 
      "document_type_id": 1,
      "sub_document_type_id": 2
      "treatment_instruction": ""
    },
    { 
      "document_type_id": 2,
      "sub_document_type_id": null
      "treatment_instruction": ""
    },
    ...
   ]}
}

400 Bad REQUEST
{
  "statusCode":400,
  "body":null,
  "error":"Invalid  person type id",
  "status":"error"
}
```
#### Document type mapping

| Document type Id | Label |
| ------------- | ------------- |
| 1 | Pièce d'identité |
| 50 | Justif domicile |
| 2 | RIB |
| 3 | Avis d'imposition [A-1] sur le revenu [A-2] |
| 61 | Verso de pièce d'identité |
| 80 | Seconde pièce d'identité |
| 51 | Kbis (CompanyID) |
| 52 | Liasse fiscale |
| 53 | Statuts entreprise (company articles) |
| 4 | Bulletin de salaire [m-1] |
| 5 | Bulletin de salaire [m-2] |
| 6 | Bulletin de salaire [m-3] |
| 26 | Dernier bulletin de pension |
| 27 | Justificatifs des 3 derniers paiements de Pôle-emploi |
| 10 | Justificatif pôle emploi [m-1] |
| 11 | Justificatif pôle emploi [m-2] |
| 12 | Justificatif pôle emploi [m-3] |
| 13 | Bilan / compte de résultats / attestation comptable de bénéfice |
| 14 | Contrat de travail ou Attestation d'employeur |
| 15 | Attestation de scolarité |
| 16 | Quittance de loyer [m-1] ou taxes foncières |
| 17 | Quittance de loyer [m-2] |
| 18 | Quittance de loyer [m-3] |
| 19 | Ancien bulletin de salaire |
| 20 | Ancien bulletin de pension |
| 21 | Ancien justificatif employeur |
| 22 | Ancienne quittance de loyer |
| 23 | Ancien avis d'imposition |
| 24 | Ancien justificatif comptable |
| 25 | Autre |
| 28 | Attestation d'hébergement |
| 29 | Taxes foncières |
| 31 | Avis d'imposition [A-2] sur le revenu [A-3] |
| 32 | Attestation de garantie FASTT |
| 41 | 3 derniers relevés mensuels Pajemploi |
| 42 | Justificatif annuel Pajemploi N-1 |
| 43 | Justificatif annuel Pajemploi N-2 |
| 44 | Kbis, Avis d'inscription RCS ou répertoire des métiers |
| 45 | Attestation pôle emploi justifiant des indemnités restantes |
| 46 | Fiche de renseignements |
| 48 | Livret de famille ou acte de mariage |
| 47 | Bulletin de salaire décembre [A-1] |
| 54 | Agrément |
| 56 | Photo |
| 57 | Facture d achat / travaux |
| 58 | Relevé de compte |
| 59 | Facture mobile |
| 60 | Signature sur fond blanc |
| 70 | Formulaire Auto-certification |
| 71 | Jugement de tutelle/curatelle |
| 72 | Simulation APL |
| 75 | Convention de service |
| 76 | Formulaire - mineur |
| 77 | Document travel |
| 78 | PV nomination |
| 73 | Certificat de contrôle |
| 55 | Certificat PEP / CDDS |
| 69 | Nachweis der Regulierungsbehörde (BaFin, Bundesbank o.ä.) |
| 67 | Wolfsberg-Fragebogen |
| 66 | Bestätigung der Aufsichtsbehörde |
| 65 | Registerauszug |
| 64 | ausgefüllter GWG-Fragebogen |
| 63 | Nachweis der Börsennotierung |
| 62 | Konzernschaubild / Beteiligungsstruktur' |
| 81 | Extrait de publication du journal officiel |
| 82 | Décret du Conseil d'état reconnaissant l'utilité publique |
| 83 | Document constatant l'agrément de votre entreprise par l'autorité compétente |
| 84 | Repartition Capital / droit de vote |
| 85 | Pdf récapitulatif |
| 86 | Autocertification FATCA `BNP personne morale` |
| 160 | Autocertification FATCA `BNP personne physique` |
| 87 | Formulaire W8 |
| 88 | Formulaire W9 |
| 93 | Certificat de perte de nationalité |
| 97 | Déclaration des bénéficiaires effectifs |
| 101 | Questionnaire activités internationales |
| 102 | Justificatif (allocations familiales, RSA,…) |
| 103 | Attestation (jugement de divorce) |
| 104 | Justificatif à votre convenence |
| 105 | Justificatif pays_domicile <> pays_fiscal |
| 121 | Dernier Rapport moral et financier |
| 122 | Liste des donateurs ou comptes détaillés certifiés par les CAC (Risque fort) |
| 123 | Justificatif d'activité (de moins de 3 mois) |
| 124 | Déclaration Ursaff |
| 172 | Questionnaire COSME |
| 173 | lettre de mission si changement EC en cours |
| 94 | Formulaire MSCQ |
| 95 | Demande de renseignements complémentaires |
| 96 | Formulaire autocertification ING |
| 131 | Justificatif de revenus |
| 132 | DRC PEP |
| 133 | Justificatif de revenu mobilier |
| 134 | Justificatif d'origine des fonds (patrimoine global) |
| 135 | Attestation enfant de diplomate / Certificat ambassade |
| 136 | Justificatif de domicile fiscal |
| 89 | Criminal record |
| 90 | Credit check |
| 91 | Attestation d'ouverture de compte |
| 92 | Délégation de signature |
| 99 | Document à signer electroniquement |
| 100 | Document signé electroniquement |
| 110 | Attestation SIREN |
| 111 | Acte constitutif |
| 112 | Prospectus du fonds ou documents équivalents permettant d'identifier la société de gestion |
| 113 | Documentation contractuelle relative au fonctionnement du PC et ses relations avec des tiers (dépositaires, etc.) |
| 114 | Extrait du Journal Officiel ou Statuts |
| 115 | Proces Verbal de l'assemblee generale qui designe le syndic |
| 116 | Statuts de l'association |
| 117 | Délégation de pouvoir du représentant légal |
| 118 | Délégation de pouvoir en bonne et due forme |
| 119 | Acte de naissance |
| 120 | Copie du jugement de mise sous curatelle/tutelle |
| 130 | Matrice à risque |
| 126 | Formulaire AutoCertification Lot 0 |
| 141 | Mandat de dépôt du porteur de projet |
| 142 | Attestation d’origine des fonds permettant le remboursement du prêt |
| 143 | Notice d’information d’octroi d’une aide Minimis |
| 144 | Attestation assurance |
| 145 | Preuve de décaissement du PH Réseau |
| 146 | PV de décision du comité réseau |
| 147 | Attestation d’origine des fonds permettant le remboursement du prêt |
| 148 | Preuve de décaissement bancaire |
| 149 | Contrat de PH Réseau |
| 150 | Contrat de prêt ou garantie entre l’entreprise et le réseau |
| 151 | Relevé de compte faisant apparaître le décaissement du prêt du réseau |
| 152 | Attestation d'identification du dirigeant |
| 153 | Grille de pré analyse |
| 154 | Formulaire signaux d'alerte |
| 155 | Questionnaire Due Dilligence |
| 156 | Compte rendu screening |
| 125 | justificatif de cotation |
| 157 | Justificatif de cofinancement |
| 158 | BPI documents personne physique |
| 159 | BPI documents personne morale |
| 165 | Analyse Réseau |
| 161 | Justificatif REGAFI |
| 162 | Extrait du Registre des Copropriétés |
| 163 | Déclaration Préfecture |
| 164 | Justificatif économique |
| 170 | Justificatif de cotation sur un portail d'information financière de référence |
| 171 | Copie du décret en Conseil d'État reconnaissant l'utilité publique |

#### Sub document type mapping

| Sub document type Id | Label |
| ------------- | ------------- |
| 1 | Carte Nationale d’Identité |
| 2 | Passeport |
| 3 | Livret de famille |
| 4 | Titre de séjour |
