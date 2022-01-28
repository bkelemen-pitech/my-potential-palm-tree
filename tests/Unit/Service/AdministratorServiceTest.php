<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Model\Request\AdministratorModel;
use App\Service\AdministratorService;
use App\Tests\BaseApiTest;
use App\Tests\Mocks\Data\AdministratorData;
use Kyc\InternalApiBundle\Exception\ResourceNotFoundException;
use Kyc\InternalApiBundle\Service\AdministratorService as InternalApiAdministratorService;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Serializer\SerializerInterface;

class AdministratorServiceTest extends BaseApiTest
{
    private $serializer;
    private ObjectProphecy $internalApiAdministratorService;
    private AdministratorService $administratorService;

    public function setUp(): void
    {
        parent::setUp();
        $this->serializer = static::getContainer()->get(SerializerInterface::class);
        $this->internalApiAdministratorService = $this->prophesize(InternalApiAdministratorService::class);

        $this->administratorService = new AdministratorService(
            $this->serializer,
            $this->internalApiAdministratorService->reveal(),
        );
    }

    public function testGetAdministratorsSuccess()
    {
        $administratorFilterModel = (new AdministratorModel())
            ->setRoles([1, 4, 6]);
        $administratorModelResponse = AdministratorData::createAdministratorModelResponse();

        $this->internalApiAdministratorService
            ->getAdministrators($administratorFilterModel)
            ->shouldBeCalledOnce()
            ->willReturn($administratorModelResponse);

        $this->assertEquals(
            AdministratorData::getAdministratorsObject(),
            $this->administratorService->getAdministrators([])
        );
    }

    public function testGetAdministratorsThrowsException()
    {
        $administratorFilterModel = (new AdministratorModel())
            ->setRoles([1, 4, 6]);
        $administratorModelResponse = AdministratorData::createAdministratorModelResponse();

        $this->internalApiAdministratorService
            ->getAdministrators($administratorFilterModel)
            ->shouldBeCalledOnce()
            ->willThrow(new ResourceNotFoundException('Invalid request'));

        $this->expectException(ResourceNotFoundException::class);
        $this->expectExceptionMessage('Invalid request');

        $this->administratorService->getAdministrators([]);
    }
}
