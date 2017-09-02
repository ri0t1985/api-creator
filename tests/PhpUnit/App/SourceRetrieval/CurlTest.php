<?php

declare(strict_types=1);

namespace PhpUnit\App\SourceRetrieval;

use App\Cache\CacheInterface;
use App\SourceRetrieval\Curl;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\SourceRetrieval\Curl
 */
final class CurlTest extends TestCase
{
    /**
     * @covers \App\SourceRetrieval\Curl::retrieveSource()
     */
    public function testRetrieveSourceWithoutCache()
    {
        $url = 'test.com';
        $curl = new CurlMock();
        $html = $curl->retrieveSource($url);
        $this->assertEquals(CurlMock::CURL_HTML, $html);
    }

    /**
     * @covers \App\SourceRetrieval\Curl::retrieveSource()
     */
    public function testRetrieveSourceWithCache()
    {
        $url = 'test.com';
        $cachedHtml = '<html><body>Hello from the cache!</body></html>';
        $cacheKey = serialize($url . CurlMock::CACHE_KEY_HTML);

        $cache = new CacheMock();
        $curl  = new CurlMock($cache);

        $this->assertFalse($cache->has($cacheKey));
        $html = $curl->retrieveSource($url);

        $this->assertEquals(CurlMock::CURL_HTML, $html);
        $this->assertTrue($cache->has($cacheKey));
        $this->assertEquals(CurlMock::CURL_HTML, $cache->get($cacheKey));

        $cache->store($cacheKey, $cachedHtml, 60);

        $this->assertEquals($cachedHtml, $cache->get($cacheKey));

        $curl = new CurlMock($cache);
        $html = $curl->retrieveSource($url);

        $this->assertNotEquals(CurlMock::CURL_HTML, $html);
        $this->assertEquals($cachedHtml, $cache->get($cacheKey));
        $this->assertEquals($cachedHtml, $html);
    }
}

class CurlMock extends Curl
{
    const CURL_HTML =  '<html><body>Hello world!</body></html>';
    protected function getSourceThroughCurl($url)
    {
        return self::CURL_HTML;
    }
}

class CacheMock implements CacheInterface
{
    protected $cache = [];

    public function has($key)
    {
        return isset($this->cache[$key]);
    }

    public function get($key)
    {
        return isset($this->cache[$key]) ? $this->cache[$key] : null;
    }

    public function remove(array $keys)
    {
        foreach($keys as $key)
        {
            unset($this->cache[$key]);
        }
    }

    public function store($key, $value, $ttl)
    {
        $this->cache[$key] = $value;
    }

}