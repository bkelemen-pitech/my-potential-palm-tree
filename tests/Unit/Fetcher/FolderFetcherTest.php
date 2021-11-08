<?php

declare(strict_types=1);

namespace App\Tests\Unit\Fetcher;

use App\DTO\Folder\FolderByIdDTO;
use App\DTO\Person\PersonDTO;
use App\DTO\Person\PersonInfoDTO;
use App\Exception\ResourceNotFoundException;
use App\Facade\InternalApi\FolderFacade;
use App\Fetcher\FolderFetcher;
use App\Model\InternalApi\Folder\FolderById;
use App\Model\InternalApi\Person\Person;
use App\Model\InternalApi\Person\PersonInfo;
use App\Services\PersonService;
use App\Tests\Mocks\Data\FolderData;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;

class FolderFetcherTest extends TestCase
{
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
        $internalApiFolderById = new FolderById();
        $internalApiFolderById
            ->setUserDossierId(1)
            ->setPartenaireDossierId('2')
            ->setStatut(3)
            ->setStatutWorkflow(1400)
            ->setAbonnement(20)
            ->setLabel(2);

        $internalAPIPerson1 = new Person();
        $internalAPIPerson1
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
        $internalAPIPerson2 = new Person();
        $internalAPIPerson2
            ->setPersonneId(2)
            ->setNom('Smithy')
            ->setPrenom('Johny')
            ->setDateNaissance('12-03-2020')
            ->setPersonneTypeId(1)
            ->setPersonneUid('1');

        $persons = [$internalAPIPerson1, $internalAPIPerson2];

        $personDTO1 = new PersonDTO();
        $personDTO1
            ->setLastName('Smith')
            ->setFirstName('John')
            ->setDateOfBirth('12-01-2020')
            ->setPersonTypeId(1)
            ->setPersonUid('1')
            ->setPersonInfo(
                [
                    (new PersonInfoDTO())
                        ->setNameInfo('dossier')
                        ->setDataInfo('39')
                        ->setSource(null)
                ]
            );

        $personDTO2 = new PersonDTO();
        $personDTO2
            ->setLastName('Smithy')
            ->setFirstName('Johny')
            ->setDateOfBirth('12-03-2020')
            ->setPersonTypeId(1)
            ->setPersonUid('1');

        $personsDTO = [$personDTO1, $personDTO2];

        $folderData = new FolderByIdDTO();
        $folderData
            ->setId(1)
            ->setPartnerFolderId('2')
            ->setStatus(3)
            ->setWorkflowStatus(1400)
            ->setLabel(2)
            ->setSubscription(20)
            ->setPersons($personsDTO);
        $this->folderFacade->getFolderById(1)->shouldBeCalledOnce()->willReturn($internalApiFolderById);
        $this->folderFacade
            ->getPersonsByFolderId(1, FolderData::GET_FOLDER_BY_ID_ORDER_FILTERS)
            ->shouldBeCalledOnce()
            ->willReturn($persons);
        $this->personService->transformPersonToDTO($internalAPIPerson1)->shouldBeCalledOnce()->willReturn($personDTO1);
        $this->personService->transformPersonToDTO($internalAPIPerson2)->shouldBeCalledOnce()->willReturn($personDTO2);

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
