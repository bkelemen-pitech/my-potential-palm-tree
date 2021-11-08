<?php

declare(strict_types=1);

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

use function json_decode;
use function json_encode;

abstract class BaseApiTest extends WebTestCase
{
    protected KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    protected function requestWithBody(
        string $method,
        string $url,
        array $body = [],
        array $headers = [],
        array $query = [],
        array $files = []
    ): ?Crawler {
        $headersAll = array_merge($this->getHeaders(), $headers);

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

    protected function getHeaders(): array
    {
        return ['CONTENT_TYPE' => 'application/json', 'HTTP_ACCEPT' => 'application/json'];
    }
}
