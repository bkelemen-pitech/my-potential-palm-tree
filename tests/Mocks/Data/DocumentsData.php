<?php

declare(strict_types=1);

namespace App\Tests\Mocks\Data;

use DateTime;
use Kyc\InternalApiBundle\Model\Request\Document\DeleteDocumentModel;
use Kyc\InternalApiBundle\Model\Request\Document\DocumentDataLogsModel;
use Kyc\InternalApiBundle\Model\Request\Document\DocumentFieldsModel;
use Kyc\InternalApiBundle\Model\Request\Document\MergeDocumentModel;
use Kyc\InternalApiBundle\Model\Request\Document\TreatDocumentModel;
use Kyc\InternalApiBundle\Model\Response\Document\DocumentByFolderModelResponse;
use Kyc\InternalApiBundle\Model\Response\Document\DocumentDataLogsModelResponse;
use Kyc\InternalApiBundle\Model\Response\Document\DocumentFieldsModelResponse;
use Kyc\InternalApiBundle\Model\Response\Document\DocumentModelResponse;

class DocumentsData
{
    public const DEFAULT_DOCUMENT_UID_TEST_DATA = '617f896a61e39';
    public const TREAT_DOCUMENT_DATA = [
        'document_uid' => self::DEFAULT_DOCUMENT_UID_TEST_DATA,
        'verification2_status' => 8,
        'agency_id' => 1,
        'folder_id' => 1,
        'administrator_id' => 1
    ];
    public const MERGE_DOCUMENTS_BODY = [
        "personUid" => "617ff03bb7c55",
        "filename" => "test_merge_documents",
        "documentTypeId" => 51,
        "documentIds" => [37441, 37442]
    ];
    public const DELETE_DOCUMENT_MODEL_DATA = [
        'documentUid' => self::DEFAULT_DOCUMENT_UID_TEST_DATA,
        'administratorId' => 1
    ];

    public static function getInternalApiDocumentsByFolderId(): array
    {
        $dataSet = [
            [36090, '60a550c9e64a4', 0, "Pièce d'identité", 1, 2, 0, 3, 18534, 50000000000003, new DateTime('2021-05-19 17:55:36')],
            [36091, '60a550c9e64a4', 36090, "Pièce d'identité", 1, 2, 3, 3, 18534, 50000000000003, new DateTime('2021-05-19 17:56:00')],
            [36092, '60a55118ae17b', 0, "Avis d'imposition [A-1] sur le revenu [A-2]", 3, 1, 0, 0, 18534, 50000000000003, new DateTime('2021-05-19 17:55:36')],
            [36093, '60a55118ae17a', 0, "Avis d'imposition [A-1] sur le revenu [A-2]", 3, 4, 0, 0, 18534, 50000000000003, new DateTime('2021-05-19 17:55:36')],
        ];

        $documentsArray = [];
        foreach ($dataSet as $data) {
            array_push($documentsArray, (new DocumentByFolderModelResponse())
                ->setDocumentId($data[0])
                ->setDocumentUid($data[1])
                ->setMasterDocumentId($data[2])
                ->setName($data[3])
                ->setDocumentTypeId($data[4])
                ->setStatus($data[5])
                ->setStatusVerification($data[6])
                ->setStatusVerification2($data[7])
                ->setPersonId($data[8])
                ->setPersonVerification($data[9])
                ->setCreation($data[10]));
        }

        return $documentsArray;
    }

    public static function getInternalApiDocumentsResponse(bool $withContent = false): DocumentModelResponse
    {
        $documentData = new DocumentModelResponse();
        $documentData
            ->setDocumentId(36090)
            ->setDocumentUid("617f896a61e39")
            ->setMasterDocumentId(0)
            ->setName("Kbis (CompanyID)")
            ->setStatus(1)
            ->setData("a:2:{s:20:\"agence_document_type\";s:2:\"11\";s:16:\"controle_couleur\";i:0;}")
            ->setCreation(new DateTime('2021-11-01T06:30:02+00:00'))
            ->setDocumentTypeId(51)
            ->setSize(181333)
            ->setVerificationStatus(0)
            ->setVerification2Status(0)
            ->setAnomaly(null)
            ->setUrl("_TEMP_COMPANYID_1129_617f896a61e39.jpg")
            ->setSignature("6bba0ea97392769fffb14df19f7c850ba4c0bfdf9d214b490e001d7bbdfe335f")
            ->setEncryption(true)
            ->setCustomerAnomaly(null)
            ->setPartnerVerificationStatus(null)
            ->setSignatureInfos(null)
            ->setType("Kbis")
            ->setOrderDocument(21)
            ->setMandatory(null)
            ->setPersonDocumentId(null)
            ->setPartnerDocumentId("passport.jpeg");

        if ($withContent) {
           $documentData->setContent("/9j/4AAQSkZJRgABAQEAYABg...");
        }

        return $documentData;
    }

    public static function getTestDocumentByUidExpectedData(bool $withContent = false): array
    {
        $expected = [
            'name' => 'Kbis (CompanyID)',
            'document_id' => 36090,
            'document_uid' => '617f896a61e39',
            'master_document_id' => 0,
            'verification_status' => 0,
            'verification2_status' => 0,
            'status' => 1,
            'creation' => '2021-11-01T06:30:02+00:00',
            'person_document_id' => null,
            'document_type_id' => 51,
            'encryption' => true,
            'customer_anomaly' => null,
            'partner_verification_status' => null,
            'data' => [
                'agence_document_type' => 11,
                'controle_couleur' => null,
            ],
            'size' => 181333,
            'anomaly' => null,
            'partner_document_id' => 'passport.jpeg',
            'url' => '_TEMP_COMPANYID_1129_617f896a61e39.jpg',
            'signature' => '6bba0ea97392769fffb14df19f7c850ba4c0bfdf9d214b490e001d7bbdfe335f',
            'signature_infos' => null,
            'order_document' => 21,
            'type' => 'Kbis',
            'mandatory' => null,
        ];

        if ($withContent) {
            $expected['content'] = "/9j/4AAQSkZJRgABAQEAYABg...";
        }

        return $expected;
    }

    public static function createTreatDocumentModel(array $data = self::TREAT_DOCUMENT_DATA)
    {
        return (new TreatDocumentModel())
            ->setDocumentUid($data['document_uid'])
            ->setVerification2Status($data['verification2_status'])
            ->setAgencyId($data['agency_id'])
            ->setAdministratorId($data['administrator_id'])
            ->setFolderId($data['folder_id']);
    }

    public static function createMergeDocumentModel(array $data = self::MERGE_DOCUMENTS_BODY, int $folderId = 1): MergeDocumentModel
    {
        return (new MergeDocumentModel())
            ->setFolderId($folderId)
            ->setPersonUid($data['personUid'])
            ->setFilename($data['filename'])
            ->setDocumentTypeId($data['documentTypeId'])
            ->setDocumentIds($data['documentIds']);
    }

    public static function createDocumentFieldsRequestModel(): DocumentFieldsModel
    {
        $documentFieldsModelRequest = new DocumentFieldsModel();
        $documentFieldsModelRequest
            ->setAgencyId(1)
            ->setDocumentTypeId(1)
            ->setPersonTypeId(1);

        return $documentFieldsModelRequest;
    }

    /**
     * @return DocumentFieldsModelResponse[]
     */
    public static function createDocumentFieldsModelResponse(): array
    {
        $documentFieldsModelResponse = new DocumentFieldsModelResponse();
        $documentFieldsModelResponse
            ->setDbFieldName('nom')
            ->setLabel('Nom')
            ->setMandatory(1)
            ->setFormat(1)
            ->setOrder(1)
            ->setHelperMethod('test')
            ->setOcrField(1)
            ->setValidatorMethod('validator');

        return [$documentFieldsModelResponse];
    }

    public static function createDocumentDataLogsRequestModel(): DocumentDataLogsModel
    {
        $documentDataLogsRequest = new DocumentDataLogsModel();
        $documentDataLogsRequest
            ->setAdministratorId(1)
            ->setDocumentIds([1, 2]);

        return $documentDataLogsRequest;
    }

    public static function createDocumentDataLogsModelResponse(?string $data = null): array
    {
        $documentDataLogsModelResponse = new DocumentDataLogsModelResponse();
        $documentDataLogsModelResponse
            ->setAdministratorId(1)
            ->setDocumentId(1)
            ->setCreatedAt(new DateTime('2020-02-02'))
            ->setVerification2Status(2)
            ->setData($data);

        return [$documentDataLogsModelResponse];
    }

    public static function createDeleteDocumentModel(array $data = self::DELETE_DOCUMENT_MODEL_DATA): DeleteDocumentModel
    {
        return (new DeleteDocumentModel())
            ->setDocumentUid($data['documentUid'])
            ->setAdministratorId($data['administratorId']);
    }
}
