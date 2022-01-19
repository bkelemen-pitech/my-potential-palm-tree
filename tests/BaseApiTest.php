<?php

declare(strict_types=1);

namespace App\Tests;

use App\Security\User;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

use Symfony\Component\Security\Core\User\UserInterface;
use function json_decode;
use function json_encode;

abstract class BaseApiTest extends WebTestCase
{
    public const USER_ID = 1;

    use ProphecyTrait;
    protected KernelBrowser $client;
    protected JWTTokenManagerInterface $tokenManager;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->tokenManager = static::getContainer()->get(JWTTokenManagerInterface::class);

    }

    protected function requestWithBody(
        string $method,
        string $url,
        array $body = [],
        array $headers = [],
        bool $withAuth = true,
        array $query = [],
        array $files = []
    ): ?Crawler {
        $headersAll = array_merge($this->getHeaders($withAuth), $headers);
        return $this->client->request(
            $method,
            $url,
            $query,
            $files,
            $headersAll,
            json_encode($body)
        );
    }

    /**
     * Gets the response
     */
    protected function getApiResponse()
    {
        return $this->client->getResponse();
    }

    /**
     * Gets the response
     */
    protected function getStatusCode()
    {
        return $this->getApiResponse()->getStatusCode();
    }

    /**
     * Gets and array with the content of the response
     */
    protected function getResponseContent()
    {
        return json_decode($this->getApiResponse()->getContent(), true);
    }

    protected function buildExceptionResponse(int $statusCode, ?array $body, string $message): array
    {
        return [
            'statusCode' => $statusCode,
            'body' => $body,
            'error' => $message,
            'status' => 'error',
        ];
    }

    protected function getHeaders(bool $withAuth = true): array
    {
        $headers = ['CONTENT_TYPE' => 'application/json', 'HTTP_ACCEPT' => 'application/json'];
        if ($withAuth) {
            $user = $this->createUser();
            $payload = $this->defineUserPayload($user);
            $headers['HTTP_X-Auth-Token'] = $this->tokenManager->createFromPayload($user, $payload);
        }

        return $headers;
    }

    protected function createUser(): UserInterface
    {
        return new User(
            'username',
            null,
            ['ROLE_1'],
            static::USER_ID,
        );
    }

    protected function defineUserPayload(UserInterface $user): array
    {
        $payload = [];
        $payload['roles'] = $user->getRoles();
        $payload['userId'] = $user->getUserId();

        return $payload;
    }
}
