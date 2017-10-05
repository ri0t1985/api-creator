<?php

namespace App\Controllers;

use App\Entities\Endpoint;
use App\Entities\Website;
use App\Exceptions\SourceRetrievalException;
use App\Helpers\HtmlParser;
use App\Services\DatabaseServiceContainer;
use App\SourceRetrieval\SourceRetrievalInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Support\Collection;

/**
 * Class DefaultController. Used to handle the user created API routes, and scrape the HTML
 * @package App\Controllers
 */
class DefaultController
{
    /**
     * @var Collection $websiteApiData Contains the structured data which is scraped from the website
     */
    protected $websiteApiData;

    /** @var DatabaseServiceContainer */
    protected $databaseServiceContainer;

    /** @var SourceRetrievalInterface */
    protected $sourceRetrievalService;

    protected $infoElements = [];

    /**
     * DefaultController constructor.
     * @param DatabaseServiceContainer $databaseServiceContainer
     * @param SourceRetrievalInterface $sourceRetrievalService
     */
    public function __construct(DatabaseServiceContainer $databaseServiceContainer, SourceRetrievalInterface $sourceRetrievalService)
    {
        $this->databaseServiceContainer = $databaseServiceContainer;
        $this->sourceRetrievalService = $sourceRetrievalService;

        // create collection so we can use _very_ helpful methods to process the data in it
        // see: https://laravel.com/docs/5.4/collections
        $this->websiteApiData = collect();
    }

    /**
     * Search the fetched API-data for specific properties with specific values
     *
     * @param $website      Website    The website element that is used
     * @param $endpoint     Endpoint   The endpoint that is used
     * @param $propertyName string     Part of the searchquery: The name of the property on which you want to search for
     * @param $searchValue  string     Part of the searchquery: The value for $propertyName to search for
     * @return JsonResponse
     */
    public function search(Website $website, Endpoint $endpoint, string $propertyName, string $searchValue): JsonResponse
    {
        $startTime = microtime();
        if (empty($propertyName) || empty($searchValue))
        {
            return new JsonResponse(
                [],
                200,
                ['Content-Type' => 'application/json']
            );
        }

        // Get the structured data from the website
        $websiteApiData = $this->getWebsiteApiData($website, $endpoint);

        // get all responses from the fetched API-data which contain properties with the value we search for
        $searchResults = $websiteApiData->filter(function ($searchResultRecord) use ($propertyName, $searchValue) {
            $propertyValue = collect($searchResultRecord)->get($propertyName, '');
            $searchPropertyHasSearchedValue = (false !== stristr($propertyValue, $searchValue));

            return $searchPropertyHasSearchedValue;
        });

        $result = [
            'info' => [
                'website_url' => $website->getUrl(),
                'endpoint'    => $endpoint->getName(),
                'searchValue' => $searchValue,
                'processingTime' => (microtime() - $startTime)
            ],
            'result' => $searchResults->toArray(),
        ];

        $this->addInfoValuesToResult($result);

        return new JsonResponse(
            $result,
            200,
            ['Content-Type' => 'application/json']
        );
    }

    /**
     * Get the structured data from a website
     *
     * @param Website $website
     * @param Endpoint $endpoint
     * @return Collection
     */
    protected function getWebsiteApiData(Website $website, Endpoint $endpoint): Collection
    {
        // only fetch websitedata when it's not done yet
        if ($this->websiteApiData->isEmpty()) {
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
    public function processEndPoint(Website $website, Endpoint $endpoint): JsonResponse
    {
        $startTime = microtime();

        $websiteApiData = $this->getWebsiteApiData($website, $endpoint);

        $result = [
            'info' => [
                'website_url' => $website->getUrl(),
                'endpoint'    => $endpoint->getName(),
//                'processingTime' => (microtime() - $startTime)
            ],
            'result' => $websiteApiData->toArray(),
        ];

        $this->addInfoValuesToResult($result);

        return new JsonResponse($result,
            200,
            ['Content-Type' => 'application/json']);
    }

    /**
     * Get the HTML from the website and use the selectors for selecting the relevant data.
     * It then takes this data and saves it as a structured array inside $this->websiteApiData
     *
     * @param Website $website
     * @param Endpoint $endpoint
     */
    protected function parseHtml(Website $website, Endpoint $endpoint)
    {

        try
        {
            $html = $this->getHtmlSource($website->getUrl());
            $htmlParser = new HtmlParser();
            $records = $htmlParser->parse($endpoint->getSelectors(), $html);
        }
        catch (SourceRetrievalException $e)
        {
            // TODO: proper error reporting
            $records = [];
            $htmlParser = new HtmlParser();
        }

        $this->websiteApiData = collect($records);
        $this->infoElements = $htmlParser->getInfoElements();
    }

    /**
     * Connect to the website by URL and fetch the HTML-source
     *
     * @param $url  string  The URL of the website of which to get the HTML-source for
     * @return string
     * @throws \Exception
     */
    protected function getHtmlSource(string $url): string
    {
        return $this->sourceRetrievalService->retrieveSource($url);
    }

    protected function addInfoValuesToResult(array $result)
    {
        foreach($this->infoElements as $key => $value) {
            $result['info'][$key] = $value;
        }
    }
}