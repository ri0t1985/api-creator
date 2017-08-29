<?php

namespace App\SourceRetrieval;


use App\Cache\CacheInterface;
use App\Exceptions\SourceRetrievalException;

class Curl implements SourceRetrievalInterface
{
    protected $cache;

    /**
     * @param string $url
     * @return string $html
     * @throws SourceRetrievalException
     */
    public function retrieveSource(string $url)
    {
        // initiate by telling curl which website it needs to act on
        $c = curl_init($url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);

        // execute the fetching
        $html = curl_exec($c);

        // when there is an error during the execution, stop everything
        if (curl_error($c))
        {
            throw new SourceRetrievalException(curl_error($c));
        }

        // nothing went wrong, so nicely close the connection
        curl_close($c);

        // return the fetched HTML
        return $html;
    }

    /**
     * SourceRetrievalInterface constructor.
     * @param CacheInterface|null $cache
     */
    public function __construct(CacheInterface $cache = null)
    {
        $this->cache = $cache;
    }
}
