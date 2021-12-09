<?php

declare(strict_types=1);

namespace App\Tests\Unit\Facade\InternalApi;

use App\Client\InternalApi\PersonClient;
use App\Enum\PersonEnum;
use App\Exception\InvalidDataException;
use App\Exception\ResourceNotFoundException;
use App\Facade\InternalApi\PersonFacade;
use App\Tests\BaseApiTest;
use App\Tests\Mocks\Data\PersonData;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpClient\Response\MockResponse;

class PersonFacadeTest extends BaseApiTest
{
    protected $personClient;
    protected $personFacade;

    public function setUp(): void
    {
        $this->personClient = $this->prophesize(PersonClient::class);
        $this->personFacade = new PersonFacade($this->personClient->reveal());
    }

    public function testAddPersonSuccess()
    {
        $addPersonModel = PersonData::createAddPersonModel();
        $addPersonResponse = PersonData::createAddPersonResponse([PersonEnum::BEPREMS_RESPONSE_PERSON_UID => PersonData::DEFAULT_PERSON_UID_TEST_DATA]);

        $this->personClient->createPerson($addPersonModel->toArray())->shouldBeCalledOnce()->willReturn($addPersonResponse);
        $this->assertEquals($addPersonResponse->getResource(), $this->personFacade->addPerson($addPersonModel));
    }

    public function testCreatePersonException()
    {
        $addPersonModel = PersonData::createAddPersonModel();
        $this->personClient->createPerson($addPersonModel->toArray())
            ->shouldBeCalledOnce()
            ->willThrow(new ClientException(new MockResponse('', ['http_code' => 400, 'url' => $_ENV['INTERNAL_API_URL'] . '/internalAPI/person/create'])));

        $this->expectException(InvalidDataException::class);
        $this->expectExceptionMessage('HTTP 400 returned for "' . $_ENV['INTERNAL_API_URL'] . '/internalAPI/person/create".');
        $this->personFacade->addPerson($addPersonModel);
    }

    public function testAssignDocumentOk()
    {
        $assignDocumentModel = PersonData::createAssignDocumentToPersonModel();
        $this->personClient->assignDocument($assignDocumentModel->toArray())->shouldBeCalledOnce();
        $this->personFacade->assignDocument($assignDocumentModel);
    }

    public function testAssignDocumentException()
    {
        $assignDocumentModel = PersonData::createAssignDocumentToPersonModel();
        $this->personClient->assignDocument($assignDocumentModel->toArray())
            ->shouldBeCalledOnce()
            ->willThrow(new ClientException(new MockResponse('', [
                'http_code' => 400,
                'url' => $_ENV['INTERNAL_API_URL'] . '/internalAPI/person/assigndocument'
            ])));

        $this->expectException(InvalidDataException::class);
        $this->expectExceptionMessage('HTTP 400 returned for "' . $_ENV['INTERNAL_API_URL'] . '/internalAPI/person/assigndocument".');
        $this->personFacade->assignDocument($assignDocumentModel);
    }

    public function testAssignDocumentNotFoundException()
    {
        $assignDocumentModel = PersonData::createAssignDocumentToPersonModel();
        $this->personClient->assignDocument($assignDocumentModel->toArray())
            ->shouldBeCalledOnce()
            ->willThrow(new ClientException(new MockResponse('', [
                'http_code' => 404,
                'url' => $_ENV['INTERNAL_API_URL'] . '/internalAPI/person/assigndocument'
            ])));

        $this->expectException(ResourceNotFoundException::class);
        $this->expectExceptionMessage('HTTP 404 returned for "' . $_ENV['INTERNAL_API_URL'] . '/internalAPI/person/assigndocument".');
        $this->personFacade->assignDocument($assignDocumentModel);
    }
}
