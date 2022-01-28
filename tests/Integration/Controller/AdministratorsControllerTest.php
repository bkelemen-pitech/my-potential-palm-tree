<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use App\Model\Request\AdministratorModel;
use App\Tests\BaseApiTest;
use App\Tests\Enum\BaseEnum;
use App\Tests\Mocks\Data\AdministratorData;
use Kyc\InternalApiBundle\Service\AdministratorService as InternalApiAdministratorService;
use Prophecy\Prophecy\ObjectProphecy;

class AdministratorsControllerTest extends BaseApiTest
{
    public const BASE_PATH = 'api/v1/administrators';

    protected ObjectProphecy $internalApiAdministratorService;

    public function setUp(): void
    {
        parent::setUp();
        $this->internalApiAdministratorService = $this->prophesize(InternalApiAdministratorService::class);
        static::getContainer()->set(InternalApiAdministratorService::class, $this->internalApiAdministratorService->reveal());
    }

    public function testGetAdministrators()
    {
        $administratorFilterModel = (new AdministratorModel())
            ->setRoles([1, 4, 6]);
        $administratorModelResponse = AdministratorData::createAdministratorModelResponse();

        $this->internalApiAdministratorService
            ->getAdministrators($administratorFilterModel)
            ->shouldBeCalledOnce()
            ->willReturn($administratorModelResponse);

        $this->requestWithBody(BaseEnum::METHOD_GET, self::BASE_PATH);
        $this->assertEquals(200, $this->getStatusCode());
        $this->assertEquals(AdministratorData::getAdministrators(), $this->getResponseContent());
    }
}
