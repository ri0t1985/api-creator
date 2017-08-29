<?php

namespace App\Cache;

interface CacheInterface
{
    public function has($key);

    public function get($key);

    public function remove(array $key);

    public function store($key, $value, $ttl);

}
