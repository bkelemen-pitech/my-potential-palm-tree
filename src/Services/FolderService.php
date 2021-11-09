<?php

namespace App\Services;

use App\Enum\FolderEnum;
use App\Fetcher\FolderFetcher;
use App\Model\Request\BaseFolderFiltersModel;
use App\Model\Response\BaseResponseFolderModel;
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
        $restructuredFolderArray = [];

        foreach ($data[FolderEnum::FOLDERS] as $folder) {
            $restructuredFolderArray[] = $this->serializer->deserialize(
                json_encode($folder),
                BaseResponseFolderModel::class,
                'json'
            )->toArray();
        }

        return [
            FolderEnum::FOLDERS => $restructuredFolderArray,
            FolderEnum::META => $data[FolderEnum::META],
        ];
    }

}