<?php

declare(strict_types=1);

namespace App\Service;

use App\Enum\UserEnum;
use App\Exception\InvalidDataException;
use App\Security\JWTTokenAuthenticator;
use App\Traits\StringTransformationTrait;
use Kyc\InternalApiBundle\Enum\AdministratorEnum;
use Kyc\InternalApiBundle\Model\Request\Document\DeleteDocumentModel;
use Kyc\InternalApiBundle\Model\Request\Document\DocumentDataLogsModel;
use Kyc\InternalApiBundle\Model\Request\Document\DocumentFieldsModel;
use Kyc\InternalApiBundle\Model\Request\Document\MergeDocumentModel;
use Kyc\InternalApiBundle\Model\Request\Document\TreatDocumentModel;
use Kyc\InternalApiBundle\Model\Response\Document\DocumentFieldsModelResponse;
use Kyc\InternalApiBundle\Model\Response\Document\DocumentModelResponse;
use Kyc\InternalApiBundle\Service\DocumentService as InternalApiDocumentService;
use Symfony\Component\Serializer\SerializerInterface;

class DocumentService
{
    use StringTransformationTrait;

    protected SerializerInterface $serializer;
    protected ValidationService $validationService;
    protected JWTTokenAuthenticator $authenticator;
    protected InternalApiDocumentService $internalApiDocumentService;

    public function __construct(
        SerializerInterface $serializer,
        JWTTokenAuthenticator $authenticator,
        InternalApiDocumentService $internalApiDocumentService
    ) {
        $this->serializer = $serializer;
        $this->authenticator = $authenticator;
        $this->internalApiDocumentService = $internalApiDocumentService;
    }

    public function getDocumentByUid(string $documentUid, bool $includeFiles): DocumentModelResponse
    {
        return $this->internalApiDocumentService->getDocumentByUid($documentUid, $includeFiles);
    }

    public function treatDocument(array $data): void
    {
        try {
        $loggedUser = $this->authenticator->getLoggedUserData();
        $treatDocumentData = $this->serializer->deserialize(
            json_encode(array_merge($data, [AdministratorEnum::ADMINISTRATOR_ID => $loggedUser[UserEnum::USER_ID]])),
            TreatDocumentModel::class,
            'json'
        );
        $this->internalApiDocumentService->treatDocument($treatDocumentData);

        }catch (\Exception $exception) {
            throw new InvalidDataException($exception->getMessage());
        }
    }

    public function mergeDocuments(array $data): void
    {
        try {
            $mergeDocumentModelData = $this->serializer->deserialize(json_encode($data), MergeDocumentModel::class, 'json');
            $this->internalApiDocumentService->mergeDocuments($mergeDocumentModelData);
        } catch (\Exception $exception) {
            throw new InvalidDataException($exception->getMessage());
        }
    }

    /**
     * @return DocumentFieldsModelResponse[]
     */
    public function getDocumentFields(array $data): array
    {
        try {
            $documentFieldsModelRequest = $this->serializer->deserialize(json_encode($data), DocumentFieldsModel::class, 'json');

            return $this->internalApiDocumentService->getDocumentFields($documentFieldsModelRequest);
        } catch (\Exception $exception) {
            throw new InvalidDataException($exception->getMessage());
        }
    }

    /**
     * @return DocumentFieldsModelResponse[]
     */
    public function getDocumentDataLogs(array $data): array
    {
        try {
            $documentDataLogs = $this->serializer->deserialize(
                json_encode($this->transformNumericValuesToInt($data)),
                DocumentDataLogsModel::class,
                'json'
            );

            return $this->internalApiDocumentService->getDocumentDataLogs($documentDataLogs);
        } catch (\Exception $exception) {
            throw new InvalidDataException($exception->getMessage());
        }
    }

    public function deleteDocumentByUid(array $data): void
    {
        $loggedUser = $this->authenticator->getLoggedUserData();
        $deleteDocumentModelData = $this->serializer->deserialize(
            json_encode(array_merge($data, [AdministratorEnum::ADMINISTRATOR_ID => $loggedUser[UserEnum::USER_ID]])),
            DeleteDocumentModel::class,
            'json'
        );

        $this->internalApiDocumentService->deleteDocumentByUid($deleteDocumentModelData);
    }
}
