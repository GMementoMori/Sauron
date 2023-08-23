<?php
namespace App\Modules\Parser\Infrastructure\Persistence\Redis;

use Psr\Cache\InvalidArgumentException;
use Redis;
use Symfony\Component\Cache\Adapter\RedisAdapter;

class RedisService
{
    private Redis $redis;
    private RedisAdapter $redisAdapter;

    public const KEY = 'last_link_';

    public function __construct()
    {
        $this->redis = RedisAdapter::createConnection(
            "redis://elios_redis:6379",
        );

        $this->redisAdapter = new RedisAdapter($this->redis);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function set(string $key, mixed $value, int $expiration = null): void
    {
        $item = $this->redisAdapter->getItem($key);
        $item->expiresAfter($expiration);
        $item->set($value);
        $this->redisAdapter->save($item);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function get(string $key): ?string
    {
        return $this->redisAdapter->getItem($key)->get();
    }

    /**
     * @throws InvalidArgumentException
     */
    public function delete(string $key): void
    {
        $this->redisAdapter->delete($key);
    }

    /**
     * @param string $parserName
     * @return string
     */
    public function getKeyCache(string $parserName): string
    {
        return static::KEY . $parserName;
    }
}
