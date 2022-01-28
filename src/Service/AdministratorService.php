<?php

namespace App\Service;

use App\Enum\AdministratorEnum;
use App\Model\Request\AdministratorModel;
use Kyc\InternalApiBundle\Service\AdministratorService as InternalApiAdministratorService;
use Symfony\Component\Serializer\SerializerInterface;

class AdministratorService
{
    protected SerializerInterface $serializer;
    protected InternalApiAdministratorService $internalApiAdministratorService;

    public function __construct(
        SerializerInterface $serializer,
        InternalApiAdministratorService $internalApiAdministratorService
    ) {
        $this->serializer = $serializer;
        $this->internalApiAdministratorService = $internalApiAdministratorService;
    }

    public function getAdministrators(array $data): array
    {
        $administratorFiltersModel = $this->serializer->deserialize(
            \json_encode($data),
            AdministratorModel::class,
            'json'
        );

        return $this->internalApiAdministratorService->getAdministrators($administratorFiltersModel);
    }
}
