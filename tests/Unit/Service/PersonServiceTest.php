<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Exception\InvalidDataException;
use App\Service\PersonService;
use App\Service\ValidationService;
use App\Tests\BaseApiTest;
use App\Tests\Mocks\Data\PersonData;
use Kyc\InternalApiBundle\Service\PersonService as InternalApiPersonService;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Serializer\SerializerInterface;

class PersonServiceTest extends BaseApiTest
{
    private ObjectProphecy $internalApiPersonService;
    private ObjectProphecy $validationService;
    private $serializer;
    private PersonService $personService;

    public function setUp(): void
    {
        parent::setUp();
        $this->internalApiPersonService = $this->prophesize(InternalApiPersonService::class);
        $this->validationService = $this->prophesize(ValidationService::class);
        $this->serializer = static::getContainer()->get(SerializerInterface::class);

        $this->personService = new PersonService(
            $this->serializer,
            $this->validationService->reveal(),
            $this->internalApiPersonService->reveal()
        );
    }

    public function testAddPersonSuccess()
    {
        $body = [
            'personTypeId' => 1,
            'agencyId' => 709
        ];

        $addPersonModel = PersonData::createAddPersonModel([$body['agencyId'], null, null, $body['personTypeId'], 1]);
        $this->internalApiPersonService->addPerson($addPersonModel)
            ->shouldBeCalledOnce()
            ->willReturn(PersonData::DEFAULT_PERSON_UID_TEST_DATA);

        $this->assertEquals(
            PersonData::DEFAULT_PERSON_UID_TEST_DATA,
            $this->personService->addPerson(1, $body)
        );
    }

    public function testAddPersonThrowsException()
    {
        $addPersonModel = PersonData::createAddPersonModel([null, null, null, null, 1]);
        $this->internalApiPersonService->addPerson($addPersonModel)
            ->shouldBeCalledOnce()
            ->willThrow(new InvalidDataException('Third party error'));

        $this->expectException(InvalidDataException::class);
        $this->expectExceptionMessage('Third party error');

        $this->personService->addPerson(1, []);
    }
}
