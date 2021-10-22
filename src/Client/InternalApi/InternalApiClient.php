<?php

declare(strict_types=1);

namespace App\Client\InternalApi;

use App\Exception\ClientException;
use App\Traits\StringTransformationTrait;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * @method array post(string $url, array $query = [], array $body = [])
 * @method array get(string $url, array $query = [], array $body = [])
 */
class InternalApiClient
{
    use StringTransformationTrait;

    public const BASE_PATH = '/internalAPI';

    protected HttpClientInterface $client;
    protected SerializerInterface $serializer;
    protected LoggerInterface $logger;
    protected ?string $url;
    protected ?string $key;
    protected ?string $id;
    protected array $obfuscateData = [];

    public function __construct(
        HttpClientInterface $client,
        SerializerInterface $serializer,
        LoggerInterface $apiLogger,
        ?string $internalApiUrl,
        ?string $internalApiKey,
        ?string $internalApiId
    ) {
        $this->client = $client;
        $this->serializer = $serializer;
        $this->logger = $apiLogger;
        $this->url = $internalApiUrl;
        $this->key = $internalApiKey;
        $this->id = $internalApiId;
    }

    public function __call($method, $arguments): string
    {
        $url = $arguments[0];
        $query = $arguments[1] ?? [];
        $body = $arguments[2] ?? [];
        $id = uniqid('INTERNAL_API');
        $jsonBody = $this->serializer->serialize($body, 'json');

        $options = [
            'query' => $query,
            'body' => $jsonBody,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'X-APP-ID' => $this->id,
                'X-API-KEY' => $this->key,
            ],
        ];
        
        try {
            $this->logger->info('[' . $id . '] Start Internal API ' . $method . ' call to ' . $url);
            $this->logger->info(
                '[' . $id . '] with query: ' . $this->serializer->serialize(
                    $this->obfuscateData($query, $this->obfuscateData),
                    'json'
                )
            );
            $this->logger->info(
                '[' . $id . '] with body: ' . $this->serializer->serialize(
                    $this->obfuscateData($body, $this->obfuscateData),
                    'json'
                )
            );

            $response = $this->client->request(strtoupper($method), $url, $options);

            $responseBody = $response->getContent();
            
            $this->logger->info('[' . $id . '] End Internal API ' . $method . ' call to ' . $url);
            $this->logger->info('[' . $id . '] with status ' . $response->getStatusCode());
        } catch (TransportExceptionInterface $exception) {
            $this->logger->error('[' . $id . '] Internal API call failed', ['exception' => $exception]);

            throw new ClientException(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                'Something went wrong!',
                $exception
            );
        }

        return $responseBody;
    }

    protected function getFullUrl(string $path): string
    {
        return trim($this->url ?? '', '/') . self::BASE_PATH . $path;
    }
}
