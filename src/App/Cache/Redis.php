<?php

namespace App\Cache;

use Predis\Client;

class Redis implements CacheInterface
{

    /** @var Client  */
    protected $client;

    /**
     * Redis constructor.
     * @param array $options
     */
    public function __construct($options = [])
    {
        $client = new Client($options);
        $this->client = $client;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return $this->getClient()->exists($key) ? true : false;
    }

    /**
     * @param string $key
     * @return string
     */
    public function get($key)
    {
        return $this->getClient()->get($key);
    }

    /**
     * @param array $keys
     * @return bool
     */
    public function remove(array $keys)
    {
        return $this->getClient()->del($keys);
    }

    /**
     * @param string $key
     * @param string $value
     * @param int $ttl Time to live in microseconds
     * @return bool
     */
    public function store($key, $value, $ttl)
    {
        return $this->getClient()->set($key, $value, 'ex', $ttl);
    }

    /**
     * @return Client
     */
    protected function getClient()
    {
        return $this->client;
    }
}