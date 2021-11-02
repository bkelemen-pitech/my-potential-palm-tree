<?php

namespace App\Services;

use App\Enum\FolderEnum;
use App\Fetcher\FolderFetcher;
use App\Model\Request\BaseFolderFiltersModel;
use Symfony\Component\Serializer\SerializerInterface;

class FolderService
{
    protected SerializerInterface $serializer;
    protected ValidationService $validationService;
    protected FolderFetcher $folderFetcher;

    public function __construct(
        FolderFetcher $folderFetcher,
        SerializerInterface $serializer,
        ValidationService $validationService
    )
    {
        $this->folderFetcher = $folderFetcher;
        $this->serializer = $serializer;
        $this->validationService = $validationService;
    }

    public function getFolders(array $data): array
    {
        $folderFiltersModel = $this->serializer->deserialize(
            json_encode($data),
            BaseFolderFiltersModel::class,
            'json'
        );
        $this->validationService->validate($folderFiltersModel);

        $data = $this->folderFetcher->getFoldersWithFilters($folderFiltersModel);

        return [
            FolderEnum::TOTAL_RECORDS => $data[FolderEnum::META][FolderEnum::TOTAL],
            FolderEnum::RECORDS => $data[FolderEnum::FOLDERS],
        ];
    }

}