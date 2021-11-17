<?php

declare(strict_types=1);

namespace Unit\Fetcher;

use App\Exception\ResourceNotFoundException;
use App\Facade\InternalApi\FolderFacade;
use App\Fetcher\FolderFetcher;
use App\Services\PersonService;
use App\Tests\Mocks\Data\FolderData;
use App\Tests\Mocks\Data\PersonData;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class FolderFetcherTest extends TestCase
{
    use ProphecyTrait;
    protected ObjectProphecy $personService;
    protected ObjectProphecy $folderFacade;
    protected FolderFetcher $folderFetcher;

    public function setUp(): void
    {
        $this->personService = $this->prophesize(PersonService::class);
        $this->folderFacade = $this->prophesize(FolderFacade::class);
        $this->folderFetcher = new FolderFetcher(
            $this->folderFacade->reveal(),
            $this->personService->reveal()
        );
    }

    public function testGetFolderData()
    {
        $folderById = FolderData::createFolderByIdEntity();
        $persons = PersonData::getFolderPersonsEntityByIdTestData();
        $personsDTO = PersonData::getFolderPersonsDTOByIdTestData();
        $folderData = FolderData::createFolderByIdDTO($personsDTO);

        $this->folderFacade->getFolderById(1)->shouldBeCalledOnce()->willReturn($folderById);
        $this->folderFacade
            ->getPersonsByFolderId(1, FolderData::GET_FOLDER_BY_ID_ORDER_FILTERS)
            ->shouldBeCalledOnce()
            ->willReturn($persons);
        $this->personService->transformPersonToDTO($persons[0])->shouldBeCalledOnce()->willReturn($personsDTO[0]);
        $this->personService->transformPersonToDTO($persons[1])->shouldBeCalledOnce()->willReturn($personsDTO[1]);

        $this->assertEquals($folderData, $this->folderFetcher->getFolderData(1, FolderData::GET_FOLDER_BY_ID_ORDER_FILTERS));
    }

    public function testGetFolderDataNotFound()
    {
        $exception = new ResourceNotFoundException('Folder with id 1 not found');
        $this->folderFacade->getFolderById(1)->shouldBeCalledOnce()->willThrow($exception);

        $this->expectException(ResourceNotFoundException::class);
        $this->expectExceptionMessage('Folder with id 1 not found');
        $this->folderFetcher->getFolderData(1, FolderData::GET_FOLDER_BY_ID_ORDER_FILTERS);
    }
}
