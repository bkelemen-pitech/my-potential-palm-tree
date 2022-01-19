<?php

declare(strict_types=1);

namespace App\Facade;

use Predis\ClientInterface;

class RedisStorageFacade
{
    protected ClientInterface $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function set($key, $value, $ttl = null): void
    {
        $this->client->set($key, $value, 'EX', $ttl);
    }

    public function get($key)
    {
        return $this->client->get($key);
    }

    public function has($key): bool
    {
        return 1 === $this->client->exists($key);
    }

    public function delete($key)
    {
        return $this->client->del($key);
    }
}
