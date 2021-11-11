<?php

declare(strict_types=1);

namespace App\Tests\Mocks\Data;

use App\Model\InternalApi\Document\DocumentByFolder;
use DateTime;

class DocumentsData
{
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
            array_push($documentsArray, (new DocumentByFolder())
                ->setDocumentId($data[0])
                ->setDocumentUid($data[1])
                ->setMasterDocumentId($data[2])
                ->setNom($data[3])
                ->setDocumentTypeId($data[4])
                ->setStatut($data[5])
                ->setStatutVerification($data[6])
                ->setStatutVerification2($data[7])
                ->setPersonneId($data[8])
                ->setPersonVerification($data[9])
                ->setCreation($data[10]));
        }

        return $documentsArray;
    }

    public static function getTestFolderPersonsDocumentExpectedData(): array
    {
        return [
            [
                'name' => "Pièce d'identité",
                'type' => 1,
                'status' => 'invalid',
                'uid' => '60a550c9e64a4',
                'documentId' => 36090,
                'documentStatus' => '3',
                'personId' => 18534
            ],
            [
                'name' => "Pièce d'identité",
                'type' => 1,
                'status' => 'invalid',
                'uid' => '60a550c9e64a4',
                'documentId' => 36091,
                'documentStatus' => '3',
                'personId' => 18534
            ],
            [
                'name' => "Avis d'imposition [A-1] sur le revenu [A-2]",
                'type' => 3,
                'status' => 'pending',
                'uid' => '60a55118ae17b',
                'documentId' => 36092,
                'documentStatus' => '0',
                'personId' => 18534
            ],
        ];
    }
}
