<?php

declare(strict_types=1);

namespace App\Tests\Unit\Facade\InternalApi;

use App\Client\InternalApi\FoldersClient;
use App\Exception\InvalidDataException;
use App\Facade\InternalApi\FolderFacade;
use App\Tests\BaseApiTest;
use App\Tests\Mocks\Data\FolderData;
use App\Tests\Mocks\Data\PersonData;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpClient\Response\MockResponse;

class FolderFacadeTest extends BaseApiTest
{
    protected $apiClient;
    protected $folderFacade;

    public function setUp(): void
    {
        $this->apiClient = $this->prophesize(FoldersClient::class);
        $this->folderFacade = new FolderFacade($this->apiClient->reveal());
    }

    public function testGetFolderByIdSuccess()
    {
        $folderById = FolderData::createFolderByIdEntity();
        $this->apiClient->getFolderById(1)->shouldBeCalledOnce()->willReturn(FolderData::createFolderByIdResponseEntity($folderById));
        $this->assertEquals($folderById, $this->folderFacade->getFolderById(1));
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
        $this->folderFacade->getFolderById(1);
    }

    public function testGetPersonsByFolderIdSuccess()
    {
        $person = PersonData::createPersonEntity();
        $this->apiClient->getPersonsByFolderId(1, [])->shouldBeCalledOnce()->willReturn(PersonData::createPersonsByFolderIdResponseData([$person]));
        $this->assertEquals([$person], $this->folderFacade->getPersonsByFolderId(1, []));
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
        $this->folderFacade->getPersonsByFolderId(1, []);
    }
}
