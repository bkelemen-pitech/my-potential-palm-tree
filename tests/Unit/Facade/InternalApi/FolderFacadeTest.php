<?php

declare(strict_types=1);

namespace App\Tests\Unit\Facade\InternalApi;

use App\Client\InternalApi\FoldersClient;
use App\Exception\InvalidDataException;
use App\Facade\InternalApi\FolderFacade;
use App\Model\InternalApi\Folder\FolderById;
use App\Model\InternalApi\Folder\GetFolderByIdResponse;
use App\Model\InternalApi\Person\Person;
use App\Model\InternalApi\Person\PersonInfo;
use App\Model\InternalApi\Person\PersonsByFolderIdResponse;
use App\Tests\BaseApiTest;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpClient\Response\MockResponse;

class FolderFacadeTest extends BaseApiTest
{
    protected $apiClient;
    protected $facade;

    public function setUp(): void
    {
        $this->apiClient = $this->prophesize(FoldersClient::class);
        $this->facade = new FolderFacade($this->apiClient->reveal());
    }

    public function testGetFolderByIdSuccess()
    {
        $internalApiFolderById = new FolderById();
        $internalApiFolderById
            ->setUserDossierId(1)
            ->setPartenaireDossierId('2')
            ->setStatut(3)
            ->setStatutWorkflow(1400)
            ->setLabel(2);
        $internalApiFolderByIdResponse = new GetFolderByIdResponse();
        $internalApiFolderByIdResponse
            ->setCode('OK')
            ->setMsg('Success')
            ->setResource($internalApiFolderById);
        $this->apiClient->getFolderById(1)->shouldBeCalledOnce()->willReturn($internalApiFolderByIdResponse);
        $this->assertEquals($internalApiFolderById, $this->facade->getFolderById(1));
    }

    public function testGetFolderByIdException()
    {
        $this->apiClient->getFolderById(1)
            ->shouldBeCalledOnce()
            ->willThrow(
                new ClientException(
                    new MockResponse(
                        '',
                        ['http_code' => 400, 'url' => $_ENV['INTERNAL_API_URL'] . '/internalAPI/folders/getfolder/folder-id/1']
                    )
                )
            );

        $this->expectException(InvalidDataException::class);
        $this->expectExceptionMessage('HTTP 400 returned for "' . $_ENV['INTERNAL_API_URL'] . '/internalAPI/folders/getfolder/folder-id/1".');
        $this->facade->getFolderById(1);
    }

    public function testGetPersonsByFolderIdSuccess()
    {
        $internalAPIPerson = new Person();
        $internalAPIPerson
            ->setPersonneId(1)
            ->setNom('Smith')
            ->setPrenom('John')
            ->setDateNaissance('12-01-2020')
            ->setPersonneTypeId(1)
            ->setPersonneUid('1')
            ->setPersonInfos(
                [
                    (new PersonInfo())
                        ->setNomInfo('dossier')
                        ->setDataInfo('39')
                        ->setSource(null)
                ]
            );
        $internalApiPersonsFolderByIdResponse = new PersonsByFolderIdResponse();
        $internalApiPersonsFolderByIdResponse
            ->setCode('OK')
            ->setMsg('Success')
            ->setResource([$internalAPIPerson]);
        $this->apiClient->getPersonsByFolderId(1, [])->shouldBeCalledOnce()->willReturn($internalApiPersonsFolderByIdResponse);
        $this->assertEquals([$internalAPIPerson], $this->facade->getPersonsByFolderId(1, []));
    }

    public function testGetPersonsByFolderIdException()
    {
        $this->apiClient->getPersonsByFolderId(1, [])
            ->shouldBeCalledOnce()
            ->willThrow(
                new ClientException(
                    new MockResponse(
                        '',
                        ['http_code' => 400, 'url' => $_ENV['INTERNAL_API_URL'] . '/internalAPI/folders/persons/folder-id/1']
                    )
                )
            );

        $this->expectException(InvalidDataException::class);
        $this->expectExceptionMessage('Request failed because of third party issues.');
        $this->facade->getPersonsByFolderId(1, []);
    }
}
