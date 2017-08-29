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
        return $this->client->exists($key) ? true : false;
    }

    /**
     * @param string $key
     * @return string
     */
    public function get($key)
    {
        return $this->client->get($key);
    }

    /**
     * @param array $keys
     * @return bool
     */
    public function remove(array $keys)
    {
        return $this->client->del($keys);
    }

    /**
     * @param string $key
     * @param string $value
     * @param int $ttl Time to live in microseconds
     * @return bool
     */
    public function store($key, $value, $ttl)
    {
        return $this->client->set($key, $value, 'ex', $ttl);
    }
}