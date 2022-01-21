<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use App\Tests\BaseApiTest;
use App\Tests\Enum\BaseEnum;
use Kyc\InternalApiBundle\Exception\ResourceNotFoundException;
use Kyc\InternalApiBundle\Model\Response\User\UserLoginModelResponse;
use Kyc\InternalApiBundle\Service\UserService;
use Prophecy\Prophecy\ObjectProphecy;

class UserControllerTest extends BaseApiTest
{
    public const USER_LOGIN_PATH = 'api/v1/users/login';
    public const USER_LOGOUT_PATH = 'api/v1/users/logout';

    protected ObjectProphecy $userService;

    public function setUp(): void
    {
        parent::setUp();
        $this->userService = $this->prophesize(UserService::class);
        static::getContainer()->set(UserService::class, $this->userService->reveal());
    }

    public function testLoginErrorWhenNoCredentials()
    {
        $requestData = [];
        $this->requestWithBody(
            BaseEnum::METHOD_POST,
            self::USER_LOGIN_PATH,
            $requestData,
            [],
            false
        );

        $this->assertEquals(401, $this->getStatusCode());
        $this->assertEquals(
            $this->buildExceptionResponse(401, null, 'Authentication credentials could not be found.'),
            $this->getResponseContent()
        );
    }

    public function testLoginErrorWhenMissingPassword()
    {
        $requestData = ["username" => "username"];
        $this->requestWithBody(
            BaseEnum::METHOD_POST,
            self::USER_LOGIN_PATH,
            $requestData,
            [],
            false
        );

        $this->assertEquals(401, $this->getStatusCode());
        $this->assertEquals(
            $this->buildExceptionResponse(401, null, 'Authentication credentials could not be found.'),
            $this->getResponseContent()
        );
        $headers = $this->client->getResponse()->headers->all();
        $this->assertArrayNotHasKey('x-extended-token', $headers);
    }

    public function testLoginErrorWhenMissingUsername()
    {
        $requestData = ["password" => "admin"];
        $this->requestWithBody(
            BaseEnum::METHOD_POST,
            self::USER_LOGIN_PATH,
            $requestData,
            [],
            false
        );

        $this->assertEquals(401, $this->getStatusCode());
        $this->assertEquals(
            $this->buildExceptionResponse(401, null, 'Authentication credentials could not be found.'),
            $this->getResponseContent()
        );
        $headers = $this->client->getResponse()->headers->all();
        $this->assertArrayNotHasKey('x-extended-token', $headers);
    }

    /**
     * @dataProvider invalidFormatCredentials
     */
    public function testLoginErrorWhenInvalidFormatCredentials(array $requestData)
    {
        $this->requestWithBody(
            BaseEnum::METHOD_POST,
            self::USER_LOGIN_PATH,
            $requestData,
            [],
            false
        );

        $this->assertEquals(401, $this->getStatusCode());
        $this->assertEquals(
            $this->buildExceptionResponse(401, null, 'An authentication exception occurred.'),
            $this->getResponseContent()
        );
        $headers = $this->client->getResponse()->headers->all();
        $this->assertArrayNotHasKey('x-extended-token', $headers);
    }

    public function invalidFormatCredentials(): array
    {
        return [
            [['username' => 'test', 'password' => '']],
            [['username' => 'test', 'password' => null]],
            [['username' => 'test', 'password' => []]],
            [['username' => '', 'password' => 'test']],
            [['username' => null, 'password' => 'test']],
            [['username' => [], 'password' => 'test']],
            [['username' => null, 'password' => '']],
        ];
    }

    public function testLoginErrorWhenWrongCredentials()
    {
        $requestData = [
            "username" => "username",
            "password" => "admin",
        ];

        $credentials = [
            "username" => "username",
            "password" => "47d640c698b41b1eb55acb3cae46b070c2d44d92dd2757ca130db4118584dcf6",
            'application' => 'backoffice'
        ];
        $exception = new ResourceNotFoundException();
        $this->userService
            ->loginUser($credentials)
            ->shouldBeCalledOnce()
            ->willThrow($exception);

        $this->requestWithBody(
            BaseEnum::METHOD_POST,
            self::USER_LOGIN_PATH,
            $requestData,
            [],
            false
        );
        $this->assertEquals(401, $this->getStatusCode());
        $this->assertEquals(
            $this->buildExceptionResponse(401, null, 'Invalid credentials.'),
            $this->getResponseContent()
        );
        $headers = $this->client->getResponse()->headers->all();
        $this->assertArrayNotHasKey('x-extended-token', $headers);
    }

    public function testLoginIsWorkingWithProperCredentials()
    {
        $requestData = [
            "username" => "username",
            "password" => "admin",
        ];
        $credentials = [
            "username" => "username",
            "password" => "47d640c698b41b1eb55acb3cae46b070c2d44d92dd2757ca130db4118584dcf6",
            'application' => 'backoffice'
        ];
        $internalApiUserLogin = new UserLoginModelResponse();
        $internalApiUserLogin
            ->setLogin('username')
            ->setRole(1)
            ->setPassword('47d640c698b41b1eb55acb3cae46b070c2d44d92dd2757ca130db4118584dcf6')
            ->setUserId(1261);

        $this->userService->loginUser($credentials)->willReturn($internalApiUserLogin);

        $this->requestWithBody(
            BaseEnum::METHOD_POST,
            self::USER_LOGIN_PATH,
            $requestData,
            [],
            false
        );

        $this->assertEquals(201, $this->getStatusCode());
        $this->assertArrayHasKey('token', $this->getResponseContent());
        $headers = $this->client->getResponse()->headers->all();
        $this->assertArrayNotHasKey('x-extended-token', $headers);
    }

    public function testLogoutSuccess()
    {
        $this->requestWithBody(
            BaseEnum::METHOD_POST,
            self::USER_LOGOUT_PATH
        );

        $this->assertEquals(204, $this->getStatusCode());
        $headers = $this->client->getResponse()->headers->all();
        $this->assertArrayNotHasKey('x-extended-token', $headers);
        $this->assertArrayNotHasKey('x-auth-token', $headers);
    }

    public function testLogoutWrongToken()
    {
        $this->requestWithBody(
            BaseEnum::METHOD_POST,
            self::USER_LOGOUT_PATH,
            [],
            ['HTTP_X-Auth-Token' => 'invalidToken']
        );

        $this->assertEquals(401, $this->getStatusCode());
        $this->assertEquals($this->buildExceptionResponse(401, null, 'Invalid JWT Token'), $this->getResponseContent());
    }

    public function testLogoutTokenNotFound()
    {
        $this->requestWithBody(
            BaseEnum::METHOD_POST,
            self::USER_LOGOUT_PATH,
            [],
            ['HTTP_X-Auth-Token' => null]
        );

        $this->assertEquals(401, $this->getStatusCode());
        $this->assertEquals($this->buildExceptionResponse(401, null, 'JWT Token not found'), $this->getResponseContent());
    }
}
