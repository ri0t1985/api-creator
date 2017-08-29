<?php

namespace App\SourceRetrieval;


use App\Cache\CacheInterface;

interface SourceRetrievalInterface
{
    const CACHE_KEY_HTML       = '-html';

    /**
     * SourceRetrievalInterface constructor.
     * @param CacheInterface|null $cache
     */
    public function __construct(CacheInterface $cache = null);

    /**
     * @param string $url
     * @return string $html
     */
    public function retrieveSource(string $url);
}