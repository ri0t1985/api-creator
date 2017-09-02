<?php

declare(strict_types=1);

namespace PhpUnit\App\Controllers;

use App\Controllers\RequestController;
use App\Entities\Endpoint;
use App\Entities\Selector;
use App\Entities\Website;
use App\Services\DatabaseServiceContainer;
use App\Services\EndPointService;
use App\Services\WebsiteService;
use App\SourceRetrieval\Curl;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @covers \App\Controllers\RequestController
 */
final class RequestControllerTest extends TestCase
{

    /**
     * @covers        \App\Controllers\RequestController::info()
     *
     * @dataProvider  infoProvider
     *
     * @param object $databaseMock
     * @param array $expected
     * @param integer $code
     */
    public function testInfo($databaseMock, $expected, $code)
    {
        /** @var Curl|\PHPUnit_Framework_MockObject_MockObject  $sourceRetrievalMock */

        $sourceRetrievalMock = $this->createMock(Curl::class);


        $controller = new RequestController(
            $databaseMock,
            $sourceRetrievalMock
        );

        $websiteName = 'test';
        $endpointName = 'test';
        $response =  $controller->info($websiteName, $endpointName);


        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(new JsonResponse($expected, $code), $response);
    }


    public function infoProvider()
    {
        /** @var Website|\PHPUnit_Framework_MockObject_MockObject $website */
        $website = $this->createMock(Website::class);
        $website->expects($this->any())->method('getName')->willReturn('test');
        $website->expects($this->any())->method('getUrl')->willReturn('test.com');

        /** @var Selector|\PHPUnit_Framework_MockObject_MockObject $selector */
        $selector = $this->createMock(Selector::class);
        $selector->expects($this->any())->method('getSelector')->willReturn('selector');
        $selector->expects($this->any())->method('getAlias')->willReturn('alias');

        /** @var Endpoint|\PHPUnit_Framework_MockObject_MockObject $endpoint */
        $endpoint = $this->createMock(Endpoint::class);
        $endpoint->expects($this->any())->method('getName')->willReturn('test');
        $endpoint->expects($this->any())->method('getSelectors')->willReturn([$selector]);

        $websiteServiceMock = $this->createMock(WebsiteService::class);
        $websiteServiceMock->expects($this->any())->method('getOneByName')->willReturn($website);

        $endpointServiceMock = $this->createMock(EndPointService::class);
        $endpointServiceMock->expects($this->any())->method('getOneByName')->willReturn($endpoint);

        /** @var DatabaseServiceContainer|\PHPUnit_Framework_MockObject_MockObject  $databaseMock */
        $databaseMock = $this->createMock(DatabaseServiceContainer::class);
        $databaseMock->expects($this->any())->method('getWebsiteService')->willReturn($websiteServiceMock);
        $databaseMock->expects($this->any())->method('getEndpointService')->willReturn($endpointServiceMock);


        $selectors = [$selector];
        $selectorInfo = [];
        foreach ($selectors as $key => $selector)
        {
            $selectorInfo[$key]['alias']   = $selector->getAlias();
            $selectorInfo[$key]['type']     = $selector->getType();
            $selectorInfo[$key]['selector'] = $selector->getSelector();
        }
        $data = [
            'website_name'  => 'test',
            'website_url'   => $website->getUrl(),
            'endpoint_name' => 'test',
            'selectors'     => $selectorInfo
        ];


        // website will be empty
        $websiteServiceMock2 = $this->createMock(WebsiteService::class);
        $websiteServiceMock2->expects($this->any())->method('getOneByName')->willReturn(null);
        $endpointServiceMock2 = $this->createMock(EndPointService::class);
        $endpointServiceMock2->expects($this->any())->method('getOneByName')->willReturn($endpoint);
        /** @var DatabaseServiceContainer|\PHPUnit_Framework_MockObject_MockObject  $databaseMock */
        $noWebsiteMock = $this->createMock(DatabaseServiceContainer::class);
        $noWebsiteMock->expects($this->any())->method('getWebsiteService')->willReturn($websiteServiceMock2);
        $noWebsiteMock->expects($this->any())->method('getEndpointService')->willReturn($endpointServiceMock2);


        // website will be empty
        $websiteServiceMock3 = $this->createMock(WebsiteService::class);
        $websiteServiceMock3->expects($this->any())->method('getOneByName')->willReturn($website);
        $endpointServiceMock3 = $this->createMock(EndPointService::class);
        $endpointServiceMock3->expects($this->any())->method('getOneByName')->willReturn(null);
        /** @var DatabaseServiceContainer|\PHPUnit_Framework_MockObject_MockObject  $databaseMock */
        $noEndpointMock = $this->createMock(DatabaseServiceContainer::class);
        $noEndpointMock->expects($this->any())->method('getWebsiteService')->willReturn($websiteServiceMock3);
        $noEndpointMock->expects($this->any())->method('getEndpointService')->willReturn($endpointServiceMock3);

        return [
            [$databaseMock,        $data, 200],
            [$noWebsiteMock,      ['No endpoint found for route: test/test'], 404],
            [$noEndpointMock,     ['No endpoint found for route: test/test'], 404],

        ];
    }
}