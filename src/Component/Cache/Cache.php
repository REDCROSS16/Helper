<?php declare(strict_types=1);

namespace App\Component\Cache;

use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Adapter\RedisAdapter;

class Cache
{
    private AbstractAdapter $cache;

    public function __construct(
        private readonly string $host,
        private readonly int $port,
        private readonly LoggerInterface $logger
    ) {
    }

    public function getConnection(): AbstractAdapter
    {
        if (!isset($this->cache)) {
            $this->cache = new FilesystemAdapter();

            if ('' !== $this->host) {
                try {
                    $client =  new \Redis();
                    $client->connect($this->host, $this->port);
                    $this->cache = new RedisAdapter($client);
                } catch (\Throwable $e) {
                    $this->logger->critical('Cache error: ' . $e->getMessage());
                }
            }
        }

        return  $this->cache;
    }
}
