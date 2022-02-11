<?php

namespace App\Enum;

use Kyc\InternalApiBundle\Enum\WorkflowStatusEnum;

class FolderEnum extends BaseEnum
{
    // PAGINATION
    public const DEFAULT_PAGE = '0';
    public const DEFAULT_LIMIT = '20';

    public const DEFAULT_ORDER_BY = 'modification';
    public const DEFAULT_ORDER = 'DESC';

    public const WORKFLOW_STATUS_HISTORY = 'workflow_status_history';

    // API PARAMETERS
    public const FILTERS = 'filters';
    public const LIMIT = 'limit';
    public const ORDER_BY = 'order_by';
    public const TEXT_SEARCH = 'text_search';
    public const TEXT_SEARCH_FIELDS = 'text_search_fields';
    public const VIEW = 'view';
    public const VIEW_CRITERIA = 'view_criteria';

    public const GET_FOLDERS_PARAMETERS_TO_UNSET = [
        self::VIEW,
        self::VIEW_CRITERIA,
    ];

    public const GET_FOLDERS_FILTER_PARAMETERS_TO_UNSET = [
        self::USER_ID,
    ];

    public const FOLDER_VIEWS = [
        self::VIEW_TO_BE_TREATED,
        self::VIEW_IN_TREATMENT,
    ];

    // API VALUES
    public const VIEW_CRITERIA_ALL_FOLDERS = 1;
    public const VIEW_CRITERIA_MY_FOLDERS = 2;
    public const VIEW_TO_BE_TREATED = 1;
    public const VIEW_IN_TREATMENT = 2;
    public const VIEW_TO_BE_TREATED_SUPERVISOR = 3;

    // API RESPONSE
    public const ASSIGNED_TO = 'assignedTo';
    public const FIRST_NAME = 'firstName';
    public const FOLDER_ID = 'folderId';
    public const FOLDERS = 'folders';
    public const LAST_NAME = 'lastName';
    public const META = 'meta';
    public const TOTAL = 'total';

    // DATABASE TABLES PREFIXES
    public const PERSON_PREFIX = 'personne.';
    public const USER_FOLDER_PREFIX = 'user_dossier.';

    // PERSON ENTITY PROPERTIES
    public const PERSON_EMAIL = 'email';
    public const PERSON_FIRST_NAME = 'first_name';
    public const PERSON_FIRST_NAME_FR = 'prenom';
    public const PERSON_LAST_NAME = 'last_name';
    public const PERSON_LAST_NAME_FR = 'nom';
    public const PERSON_TELEPHONE = 'telephone';

    public const PERSON_ENTITY_PROPERTIES_EN_TO_FR = [
        self::PERSON_EMAIL => self::PERSON_EMAIL,
        self::PERSON_FIRST_NAME => self::PERSON_FIRST_NAME_FR,
        self::PERSON_LAST_NAME => self::PERSON_LAST_NAME_FR,
        self::PERSON_TELEPHONE => self::PERSON_TELEPHONE,
    ];

    // USER FOLDER ENTITY PROPERTIES
    public const ACTIVE_SCREENING = 'active_screening';
    public const ACTIVE_SCREENING_FR = 'screening_actif';
    public const AGENCY_ID_REFERENCE = 'agency_id_reference';
    public const AGENCY_ID_REFERENCE_FR = 'agence_id_ref';
    public const BACKEND_PROCESSING = 'backend_processing';
    public const BACKEND_PROCESSING_FR = 'traitement_backend';
    public const BASE_AGREEMENT_REFERENCE = 'base_agreement_reference';
    public const BASE_AGREEMENT_REFERENCE_FR = 'reference_agrement_basse';
    public const BIS_PARTNER_FOLDER_ID = 'bis_partner_folder_id';
    public const BIS_PARTNER_FOLDER_ID_FR = 'partenaire_bis_dossier_id';
    public const BLOCK_DATE = 'block_date';
    public const BLOCK_DATE_FR = 'date_blocage';
    public const BLOCKED_FOLDER = 'blocked_folder';
    public const BLOCKED_FOLDER_FR = 'dossier_bloque';
    public const CALLBACK = 'callback';
    public const CALLBACK_FR = 'rappel';
    public const CERTIFICATION_DATE = 'certification_date';
    public const CERTIFICATION_DATE_FR = 'date_certification';
    public const CREATED_AT = 'created_at';
    public const CREATED_AT_FR = 'creation';
    public const CREDIT = 'credit';
    public const EMPTY_PROCESSING = 'empty_processing';
    public const EMPTY_PROCESSING_FR = 'traitement_a_blanc';
    public const FOLDER = 'folder';
    public const FOLDER_FR = 'dossier';
    public const GOOD_ID = 'bien_id';
    public const GOOD_ID_FR = 'good_id';
    public const GOODS_ID = 'goods_id';
    public const GOODS_ID_FR = 'biens_id';
    public const INITIAL_PROCESSING_VALUE = 'initial_processing_value';
    public const INITIAL_PROCESSING_VALUE_FR = 'init_score_traitement';
    public const LABEL = 'label';
    public const LAST_MODIFICATION = 'last_modification';
    public const LOT_NUMBER = 'lot_number';
    public const LOT_NUMBER_FR = 'num_lot';
    public const MAX_PEP_RISK = 'max_pep_risk';
    public const MAX_PEP_RISK_FR = 'risque_pep_max';
    public const MAX_PERSON = 'max_person';
    public const MAX_PERSON_FR = 'max_personne';
    public const MOVED_IN_TODO = 'moved_in_todo';
    public const OPTIN_AGENCY = 'optin_agency';
    public const OPTIN_AGENCY_FR = 'optin_agence';
    public const OTP = 'otp';
    public const OTP_TELEPHONE = 'otp_telephone';
    public const PARTNER_FOLDER_ID = 'partner_folder_id';
    public const PARTNER_FOLDER_ID_FR = 'partenaire_dossier_id';
    public const PREAGREMENT = 'preagrement';
    public const PROCESSING_STATUS = 'processing_status';
    public const PROCESSING_STATUS_FR = 'treatment_status';
    public const PROCESSING_TIME = 'processing_time';
    public const PROCESSING_TIME_FR = 'duree_traitement';
    public const PROCESSING_VALUE = 'processing_value';
    public const PROCESSING_VALUE_FR = 'score_traitement';
    public const REASON = 'reason';
    public const REASON_FR = 'raison';
    public const RECEPTION_CHANNEL = 'reception_channel';
    public const RECEPTION_CHANNEL_FR = 'canal_reception';
    public const RECERTIFICATION_CYCLE = 'recertification_cycle';
    public const RECERTIFICATION_CYCLE_FR = 'cycle_recertification';
    public const RECERTIFICATION_DATE = 'recertification_date';
    public const RECERTIFICATION_DATE_FR = 'date_recertification';
    public const RECERTIFICATION_PARENT_ID = 'recertification_parent_id';
    public const RECERTIFICATION_RISK_LEVEL = 'recertification_risk_level';
    public const RECERTIFICATION_RISK_LEVEL_FR = 'recertification_niveau_risque';
    public const RISK = 'risk';
    public const RISK_FR = 'risque';
    public const RISK_LABEL = 'risk_label';
    public const RISK_LABEL_FR = 'risque_label';
    public const SALARY = 'salary';
    public const SALARY_FR = 'salaire';
    public const SIGNATURE_REDIRECT_URL = 'signature_redirect_url';
    public const SIGNATURE_REDIRECT_URL_FR = 'url_signature_redirect';
    public const SIGNED_DOCUMENT = 'signed_document';
    public const SIGNED_DOCUMENT_FR = 'document_signe';
    public const SIZE = 'size';
    public const SIZE_FR = 'gabarit';
    public const STATUS = 'status';
    public const STATUS_FR = 'statut';
    public const STATUS_VERIFICATION = 'status_verification';
    public const STATUS_VERIFICATION_FR = 'statut_verification';
    public const STATUS_VERIFICATION_2 = 'status_verification2';
    public const STATUS_VERIFICATION_2_FR = 'statut_verification2';
    public const SUPERVISION = 'supervision';
    public const THIRD_PARTY_VALIDATION_DATE = 'third_party_validation_date';
    public const THIRD_PARTY_VALIDATION_DATE_FR = 'date_validation_tiers';
    public const TO_BE_DELETED = 'to_be_deleted';
    public const UNIVERSAL_FOLDER = 'universal_folder';
    public const UNIVERSAL_FOLDER_FR = 'dossier_universel';
    public const UPDATED_AT = 'updated_at';
    public const UPDATED_AT_FR = 'modification';
    public const USER_FOLDER_ID = 'user_folder_id';
    public const USER_FOLDER_ID_FR = 'user_dossier_id';
    public const USER_ID = 'user_id';
    public const USER_PARENT_FOLDER_ID = 'user_parent_folder_id';
    public const USER_PARENT_FOLDER_ID_FR = 'user_dossier_id_parent';
    public const VERIFICATION_LEVEL = 'verification_level';
    public const VERIFICATION_LEVEL_FR = 'niveau_verification';
    public const VERSION = 'version';
    public const VISIBLE_CERTIFICATE = 'visible_certificate';
    public const VISIBLE_CERTIFICATE_FR = 'certificat_visible';
    public const WORKFLOW_STATUS = 'workflow_status';
    public const WORKFLOW_STATUS_FR = 'statut_workflow';
    public const WORKFLOW_STATUS_DATE = 'workflow_status_date';
    public const WORKFLOW_STATUS_DATE_FR = 'date_statut_workflow';


    public const VIEW_TO_BE_TREATED_TAB = [
        self::WORKFLOW_STATUS => [
            WorkflowStatusEnum::STATUT_WORKFLOW_TRAITER_PAR_WEBHELP,
        ],
    ];
    public const VIEW_IN_TREATMENT_TAB = [
        self::WORKFLOW_STATUS => [
            WorkflowStatusEnum::STATUT_WORKFLOW_PRISE_EN_CHARGE_PAR_WEBHELP,
            WorkflowStatusEnum::STATUT_WORKFLOW_SUPERVISER_PAR_WEBHELP,
            WorkflowStatusEnum::STATUT_WORKFLOW_SUPERVISER_PAR_WEBHELP_1,
            WorkflowStatusEnum::STATUT_WORKFLOW_SUPERVISER_PAR_WEBHELP_2,
        ],
    ];
    public const VIEW_TO_BE_TREATED_SUPERVISOR_TAB = [
        self::WORKFLOW_STATUS => [
            WorkflowStatusEnum::STATUT_WORKFLOW_TRAITER_PAR_WEBHELP_SUPERVISOR,
        ],
    ];

    public const WORKFLOW_STATUS_BY_VIEW = [
        self::VIEW_TO_BE_TREATED => self::VIEW_TO_BE_TREATED_TAB,
        self::VIEW_IN_TREATMENT => self::VIEW_IN_TREATMENT_TAB,
        self::VIEW_TO_BE_TREATED_SUPERVISOR => self::VIEW_TO_BE_TREATED_SUPERVISOR_TAB,
    ];

    public const FOLDER_ENTITY_PROPERTIES = [
        self::ACTIVE_SCREENING,
        self::AGENCY_ID_REFERENCE,
        self::BACKEND_PROCESSING,
        self::BASE_AGREEMENT_REFERENCE,
        self::BIS_PARTNER_FOLDER_ID,
        self::BLOCK_DATE,
        self::BLOCKED_FOLDER,
        self::CALLBACK,
        self::CERTIFICATION_DATE,
        self::CREATED_AT,
        self::CREDIT,
        self::EMPTY_PROCESSING,
        self::FOLDER,
        self::GOOD_ID,
        self::GOODS_ID,
        self::INITIAL_PROCESSING_VALUE,
        self::LABEL,
        self::LAST_MODIFICATION,
        self::LOT_NUMBER,
        self::MAX_PEP_RISK,
        self::MAX_PERSON,
        self::MOVED_IN_TODO,
        self::OPTIN_AGENCY,
        self::OTP,
        self::OTP_TELEPHONE,
        self::PARTNER_FOLDER_ID,
        self::PREAGREMENT,
        self::PROCESSING_STATUS,
        self::PROCESSING_TIME,
        self::PROCESSING_VALUE,
        self::REASON,
        self::RECEPTION_CHANNEL,
        self::RECERTIFICATION_CYCLE,
        self::RECERTIFICATION_DATE,
        self::RECERTIFICATION_PARENT_ID,
        self::RECERTIFICATION_RISK_LEVEL,
        self::RISK,
        self::RISK_LABEL,
        self::SALARY,
        self::SIGNATURE_REDIRECT_URL,
        self::SIGNED_DOCUMENT,
        self::SIZE,
        self::STATUS,
        self::STATUS_VERIFICATION,
        self::STATUS_VERIFICATION_2,
        self::SUPERVISION,
        self::THIRD_PARTY_VALIDATION_DATE,
        self::TO_BE_DELETED,
        self::UNIVERSAL_FOLDER,
        self::UPDATED_AT,
        self::USER_FOLDER_ID,
        self::USER_ID,
        self::USER_PARENT_FOLDER_ID,
        self::VERIFICATION_LEVEL,
        self::VERSION,
        self::VISIBLE_CERTIFICATE,
        self::WORKFLOW_STATUS,
        self::WORKFLOW_STATUS_DATE,
    ];

    public const FOLDER_ENTITY_PROPERTIES_FR = [
        self::ACTIVE_SCREENING_FR,
        self::AGENCY_ID_REFERENCE_FR,
        self::BACKEND_PROCESSING_FR,
        self::BASE_AGREEMENT_REFERENCE_FR,
        self::BIS_PARTNER_FOLDER_ID_FR,
        self::BLOCK_DATE_FR,
        self::BLOCKED_FOLDER_FR,
        self::CALLBACK_FR,
        self::CERTIFICATION_DATE_FR,
        self::CREATED_AT_FR,
        self::CREDIT,
        self::EMPTY_PROCESSING_FR,
        self::FOLDER_FR,
        self::GOOD_ID_FR,
        self::GOODS_ID_FR,
        self::INITIAL_PROCESSING_VALUE_FR,
        self::LABEL,
        self::LAST_MODIFICATION,
        self::LOT_NUMBER_FR,
        self::MAX_PEP_RISK_FR,
        self::MAX_PERSON_FR,
        self::MOVED_IN_TODO,
        self::OPTIN_AGENCY_FR,
        self::OTP,
        self::OTP_TELEPHONE,
        self::PARTNER_FOLDER_ID_FR,
        self::PREAGREMENT,
        self::PROCESSING_STATUS_FR,
        self::PROCESSING_TIME_FR,
        self::PROCESSING_VALUE_FR,
        self::REASON_FR,
        self::RECEPTION_CHANNEL_FR,
        self::RECERTIFICATION_CYCLE_FR,
        self::RECERTIFICATION_DATE_FR,
        self::RECERTIFICATION_PARENT_ID,
        self::RECERTIFICATION_RISK_LEVEL_FR,
        self::RISK_FR,
        self::RISK_LABEL_FR,
        self::SALARY_FR,
        self::SIGNATURE_REDIRECT_URL_FR,
        self::SIGNED_DOCUMENT_FR,
        self::SIZE_FR,
        self::STATUS_FR,
        self::STATUS_VERIFICATION_FR,
        self::STATUS_VERIFICATION_2_FR,
        self::SUPERVISION,
        self::THIRD_PARTY_VALIDATION_DATE_FR,
        self::TO_BE_DELETED,
        self::UNIVERSAL_FOLDER_FR,
        self::UPDATED_AT_FR,
        self::USER_FOLDER_ID_FR,
        self::USER_ID,
        self::USER_PARENT_FOLDER_ID_FR,
        self::VERIFICATION_LEVEL_FR,
        self::VERSION,
        self::VISIBLE_CERTIFICATE_FR,
        self::WORKFLOW_STATUS_FR,
        self::WORKFLOW_STATUS_DATE_FR,
    ];

    public const FOLDER_ENTITY_PROPERTIES_EN_TO_FR = [
        self::ACTIVE_SCREENING => self::ACTIVE_SCREENING_FR,
        self::AGENCY_ID_REFERENCE => self::AGENCY_ID_REFERENCE_FR,
        self::BACKEND_PROCESSING => self::BACKEND_PROCESSING_FR,
        self::BASE_AGREEMENT_REFERENCE => self::BASE_AGREEMENT_REFERENCE_FR,
        self::BIS_PARTNER_FOLDER_ID => self::BASE_AGREEMENT_REFERENCE_FR,
        self::BLOCK_DATE => self::BLOCK_DATE_FR,
        self::BLOCKED_FOLDER => self::BLOCKED_FOLDER_FR,
        self::CALLBACK => self::CALLBACK_FR,
        self::CERTIFICATION_DATE => self::CERTIFICATION_DATE_FR,
        self::CREATED_AT => self::CREATED_AT_FR,
        self::CREDIT => self::CREDIT,
        self::EMPTY_PROCESSING => self::EMPTY_PROCESSING_FR,
        self::FOLDER => self::FOLDER_FR,
        self::GOOD_ID => self::GOOD_ID_FR,
        self::GOODS_ID => self::GOODS_ID_FR,
        self::INITIAL_PROCESSING_VALUE => self::INITIAL_PROCESSING_VALUE_FR,
        self::LABEL => self::LABEL,
        self::LAST_MODIFICATION => self::LAST_MODIFICATION,
        self::LOT_NUMBER => self::LOT_NUMBER_FR,
        self::MAX_PEP_RISK => self::MAX_PEP_RISK_FR,
        self::MAX_PERSON => self::MAX_PERSON_FR,
        self::MOVED_IN_TODO => self::MOVED_IN_TODO,
        self::OPTIN_AGENCY => self::OPTIN_AGENCY_FR,
        self::OTP => self::OTP,
        self::OTP_TELEPHONE => self::OTP_TELEPHONE,
        self::PARTNER_FOLDER_ID => self::PARTNER_FOLDER_ID_FR,
        self::PREAGREMENT => self::PREAGREMENT,
        self::PROCESSING_STATUS => self::PROCESSING_STATUS_FR,
        self::PROCESSING_TIME => self::PROCESSING_TIME_FR,
        self::PROCESSING_VALUE => self::PROCESSING_VALUE_FR,
        self::REASON => self::REASON_FR,
        self::RECEPTION_CHANNEL => self::RECEPTION_CHANNEL_FR,
        self::RECERTIFICATION_CYCLE => self::RECERTIFICATION_CYCLE_FR,
        self::RECERTIFICATION_DATE => self::RECERTIFICATION_DATE_FR,
        self::RECERTIFICATION_PARENT_ID => self::RECERTIFICATION_PARENT_ID,
        self::RECERTIFICATION_RISK_LEVEL => self::RECERTIFICATION_RISK_LEVEL_FR,
        self::RISK => self::RISK_FR,
        self::RISK_LABEL => self::RISK_LABEL_FR,
        self::SALARY => self::SALARY_FR,
        self::SIGNATURE_REDIRECT_URL => self::SIGNATURE_REDIRECT_URL_FR,
        self::SIGNED_DOCUMENT => self::SIGNED_DOCUMENT_FR,
        self::SIZE => self::SIZE_FR,
        self::STATUS => self::STATUS_FR,
        self::STATUS_VERIFICATION => self::STATUS_VERIFICATION_FR,
        self::STATUS_VERIFICATION_2 => self::STATUS_VERIFICATION_2_FR,
        self::SUPERVISION => self::SUPERVISION,
        self::THIRD_PARTY_VALIDATION_DATE => self::THIRD_PARTY_VALIDATION_DATE_FR,
        self::TO_BE_DELETED => self::TO_BE_DELETED,
        self::UNIVERSAL_FOLDER => self::UNIVERSAL_FOLDER_FR,
        self::UPDATED_AT => self::UPDATED_AT_FR,
        self::USER_FOLDER_ID => self::USER_FOLDER_ID_FR,
        self::USER_ID => self::USER_ID,
        self::USER_PARENT_FOLDER_ID => self::USER_PARENT_FOLDER_ID_FR,
        self::VERIFICATION_LEVEL => self::VERIFICATION_LEVEL_FR,
        self::VERSION => self::VERSION,
        self::VISIBLE_CERTIFICATE => self::VISIBLE_CERTIFICATE_FR,
        self::WORKFLOW_STATUS => self::WORKFLOW_STATUS_FR,
        self::WORKFLOW_STATUS_DATE => self::WORKFLOW_STATUS_DATE_FR,
    ];
}
