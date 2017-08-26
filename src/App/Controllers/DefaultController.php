<?php

namespace App\Controllers;

use App\Services\DatabaseServiceContainer;
use App\Services\WebsiteService;
use Sunra\PhpSimple\HtmlDomParser;
use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Support\Collection;


class DefaultController
{
    /**
     * @var Collection $websiteApiData Contains the structured data which is scraped from the website
     */
    protected $websiteApiData;

    /** @var WebsiteService  */
    protected $databaseServiceContainer;

    /**
     * DefaultController constructor.
     * @param DatabaseServiceContainer $databaseServiceContainer
     */
    public function __construct(DatabaseServiceContainer $databaseServiceContainer)
    {
        $this->databaseServiceContainer = $databaseServiceContainer;

        // create collection so we can use _very_ helpful methods to process the data in it
        // see: https://laravel.com/docs/5.4/collections
        $this->websiteApiData = collect();
    }

    /**
     * Search the fetched API-data for specific properties with specific values
     *
     * @param $website     array    Information about the website with at least the (db) id as element
     * @param $endpoint     array   Information about the endpoint with at least the (db) id as element
     * @param $propertyName string  Part of the searchquery: The name of the property on which you want to search for
     * @param $searchValue  string  Part of the searchquery: The value for $propertyName to search for
     * @return JsonResponse
     */
    public function search(array $website, array $endpoint, string $propertyName, string $searchValue) : JsonResponse
    {
        // Get the structured data from the website
        $websiteApiData =  $this->getWebsiteApiData($website, $endpoint);

        // get all responses from the fetched API-data which contain properties with the value we search for
        $searchResults = $websiteApiData->filter(function($searchResultRecord) use ($propertyName, $searchValue) {
            $propertyValue = collect($searchResultRecord)->get($propertyName, '');
            $searchPropertyHasSearchedValue = (false !== stristr($propertyValue, $searchValue));

            return $searchPropertyHasSearchedValue;
        });

        return new JsonResponse(
            $searchResults->toArray(),
            200,
            ['Content-Type' => 'application/json']
        );
    }

    /**
     * Get the structured data from a website
     *
     * @param array $website
     * @param array $endpoint
     * @return Collection
     */
    protected function getWebsiteApiData(array $website, array $endpoint) : Collection
    {
        // only fetch websitedata when it's not done yet
        if ( $this->websiteApiData->isEmpty() ){
            $this->parseHtml($website, $endpoint);
        }

        return $this->websiteApiData;
    }

    /**
     *
     * @param $website
     * @param $endpoint
     * @return JsonResponse
     */
    public function processEndPoint(array $website, array $endpoint) : JsonResponse
    {
        $websiteApiData =  $this->getWebsiteApiData($website, $endpoint);

        return new JsonResponse($websiteApiData->toArray(), 200, ['Content-Type' => 'application/json']);
    }

    /**
     * Get the HTML from the website and use the selectors for selecting the relevant data.
     * It then takes this data and saves it as a structured array inside $this->websiteApiData
     *
     * @param $domSelectors array   Collection domSelectors
     * @param $websiteUrl   string  The url of the website which is going to be 'scraped'
     * @throws \Exception
     * @internal param $ [] $domSelectors
     */
    protected function parseHtml(array $website, array $endpoint)
    {
        // get the selectors with which we will be able to point out
        // relevant data on the target website
        $selectors = $this
            ->databaseServiceContainer
            ->getSelectorService()
            ->getAllByWebsiteIdAndEndpointId($website['id'], $endpoint['id']);

        $htmlSource = $this->getHtmlSource($website['url']);

        $html = HtmlDomParser::str_get_html($htmlSource);

        foreach ($selectors as $selector) {
            foreach ($html->find($selector['selector']) as $key => $element) {

                if (isset($element->src) && !empty($element->src))
                {
                    $src = trim(strip_tags((string)$element->src));

                    $records[$key][$selector['alias']] = $src;
                }
                else {
                    $records[$key][$selector['alias']] = trim(strip_tags((string)$element));
                }
            }
        }

        $this->websiteApiData = collect($records);
    }

    /**
     * Connect to the website by URL and fetch the HTML-source
     *
     * @param $url  string  The URL of the website of which to get the HTML-source for
     * @return string
     * @throws \Exception
     */
    protected function getHtmlSource(string $url) : string
    {
        // initiate by telling curl which website it needs to act on
        $c = curl_init($url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);

        // execute the fetching
        $html = curl_exec($c);

        // when there is an error during the execution, stop everything
        if (curl_error($c))
        {
            throw new \Exception(curl_error($c));
        }

        // nothing went wrong, so nicely close the connection
        curl_close($c);

        // return the fetched HTML
        return $html;
    }

    protected function convertPathToExact($websiteUrl, $path)
    {
        // check if src is relative
        if (false !== file_exists($path)) {
            return $path;
        }

        $exactUrl =  parse_url($websiteUrl, PHP_URL_SCHEME).'://'
            . parse_url($websiteUrl, PHP_URL_HOST)
            . $path;

        if (false !== file_get_contents($exactUrl))
        {
            return $exactUrl;
        }

    }
}