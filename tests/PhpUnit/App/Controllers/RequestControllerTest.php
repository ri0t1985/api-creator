<?php

declare(strict_types=1);

namespace PhpUnit\App\Controllers;

use App\Controllers\RequestController;
use App\Entities\Endpoint;
use App\Entities\Selector;
use App\Entities\SelectorOption;
use App\Entities\Website;
use App\Services\DatabaseServiceContainer;
use App\Services\EndPointService;
use App\Services\WebsiteService;
use App\SourceRetrieval\Curl;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

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
     * @param DatabaseServiceContainer $databaseMock
     * @param array $expected
     * @param integer $code
     */
    public function testInfo($databaseMock, $expected, $code)
    {
        /** @var Curl|\PHPUnit_Framework_MockObject_MockObject $sourceRetrievalMock */
        $sourceRetrievalMock = $this->createMock(Curl::class);
        $controller = new RequestController(
            $databaseMock,
            $sourceRetrievalMock
        );

        $websiteName = 'test';
        $endpointName = 'test';
        $response = $controller->info($websiteName, $endpointName);


        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals($code, $response->getStatusCode());
        $this->assertEquals($expected, json_decode($response->getContent(), true));    }


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
        $selector->expects($this->any())->method('getOptions')->willReturn([]);

        /** @var Endpoint|\PHPUnit_Framework_MockObject_MockObject $endpoint */
        $endpoint = $this->createMock(Endpoint::class);
        $endpoint->expects($this->any())->method('getName')->willReturn('test');
        $endpoint->expects($this->any())->method('getSelectors')->willReturn([$selector]);

        $websiteServiceMock = $this->createMock(WebsiteService::class);
        $websiteServiceMock->expects($this->any())->method('getOneByName')->willReturn($website);

        $endpointServiceMock = $this->createMock(EndPointService::class);
        $endpointServiceMock->expects($this->any())->method('getOneByName')->willReturn($endpoint);

        /** @var DatabaseServiceContainer|\PHPUnit_Framework_MockObject_MockObject $databaseMock */
        $databaseMock = $this->createMock(DatabaseServiceContainer::class);
        $databaseMock->expects($this->any())->method('getWebsiteService')->willReturn($websiteServiceMock);
        $databaseMock->expects($this->any())->method('getEndpointService')->willReturn($endpointServiceMock);


        $selectors = [$selector];
        $selectorInfo = [];
        foreach ($selectors as $key => $selector) {
            $selectorInfo[$key]['alias'] = $selector->getAlias();
            $selectorInfo[$key]['type'] = $selector->getType();
            $selectorInfo[$key]['selector'] = $selector->getSelector();
        }
        $data = [
            'website_name' => 'test',
            'website_url' => $website->getUrl(),
            'endpoint_name' => 'test',
            'selectors' => $selectorInfo
        ];


        // website will be empty
        $websiteServiceMock2 = $this->createMock(WebsiteService::class);
        $websiteServiceMock2->expects($this->any())->method('getOneByName')->willReturn(null);
        $endpointServiceMock2 = $this->createMock(EndPointService::class);
        $endpointServiceMock2->expects($this->any())->method('getOneByName')->willReturn($endpoint);
        /** @var DatabaseServiceContainer|\PHPUnit_Framework_MockObject_MockObject $databaseMock */
        $noWebsiteMock = $this->createMock(DatabaseServiceContainer::class);
        $noWebsiteMock->expects($this->any())->method('getWebsiteService')->willReturn($websiteServiceMock2);
        $noWebsiteMock->expects($this->any())->method('getEndpointService')->willReturn($endpointServiceMock2);


        // website will be empty
        $websiteServiceMock3 = $this->createMock(WebsiteService::class);
        $websiteServiceMock3->expects($this->any())->method('getOneByName')->willReturn($website);
        $endpointServiceMock3 = $this->createMock(EndPointService::class);
        $endpointServiceMock3->expects($this->any())->method('getOneByName')->willReturn(null);
        /** @var DatabaseServiceContainer|\PHPUnit_Framework_MockObject_MockObject $databaseMock */
        $noEndpointMock = $this->createMock(DatabaseServiceContainer::class);
        $noEndpointMock->expects($this->any())->method('getWebsiteService')->willReturn($websiteServiceMock3);
        $noEndpointMock->expects($this->any())->method('getEndpointService')->willReturn($endpointServiceMock3);

        return [
            [$databaseMock, $data, 200],
            [$noWebsiteMock, ['No endpoint found for route: test/test'], 404],
            [$noEndpointMock, ['No endpoint found for route: test/test'], 404],

        ];
    }

    /**
     * @dataProvider functionTestProvider
     *
     * @param Request $request
     * @param array $expected
     * @param integer $code
     */
    public function testTest($request, $expected, $code)
    {
        /** @var Curl|\PHPUnit_Framework_MockObject_MockObject $sourceRetrievalMock */
        $sourceRetrievalMock = $this->createMock(Curl::class);
        $sourceRetrievalMock->expects($this->any())->method('retrieveSource')
            ->willReturn('<html><body><a class="test">link</a><br/><a class="test">link</a></body></html>');

        /** @var DatabaseServiceContainer|\PHPUnit_Framework_MockObject_MockObject $databaseMock */
        $databaseMock = $this->createMock(DatabaseServiceContainer::class);

        // website will be empty
        $websiteServiceMock = $this->createMock(WebsiteService::class);
        $websiteServiceMock->expects($this->any())->method('getOneByName')->willReturn(null);
        $endpointServiceMock = $this->createMock(EndPointService::class);
        $endpointServiceMock->expects($this->any())->method('getOneByName')->willReturn(null);

        /** @var DatabaseServiceContainer|\PHPUnit_Framework_MockObject_MockObject $databaseMock */
        $databaseMock->expects($this->any())->method('getWebsiteService')->willReturn($websiteServiceMock);
        $databaseMock->expects($this->any())->method('getEndpointService')->willReturn($endpointServiceMock);


        $controller = new RequestController(
            $databaseMock,
            $sourceRetrievalMock
        );

        $response = $controller->test($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals($code, $response->getStatusCode());
        $content = $response->getContent();
        $json_content = json_decode($content, true);
        $this->assertEquals($expected, $json_content);    }

    /**
     * @see RequestControllerTest::testTest()
     */
    public function functionTestProvider()
    {
        $dataSet1 = [
            'website_name' => 'test',
            'website_url' => 'test',
            'endpoints' => [[
                'name' => 'test',
                'selectors' => [[
                    'alias' => 'test',
                    'selector' => 'h5'
                ]],
            ]],
        ];

        $dataSet2 = [
            'website_name' => '',
            'website_url' => '',
            'endpoints' => [],
        ];

        $dataSet3 = [
            'website_name' => 'test',
            'website_url' => 'test',
            'endpoints' => [[
                'name' => 'test',

            ]],
        ];

        $dataSet4 = [
            'website_name' => 'test',
            'website_url' => 'test',
            'endpoints' => [[
                'name' => 'test',
                'selectors' => [[
                    'type' => 'wrong'
                ]],
            ]],
        ];

        $dataSet5 = [
            'website_name' => 'test',
            'website_url' => 'test',
            'endpoints' => [[
                'name' => 'test',
                'selectors' => [[
                    'alias' => 'test',
                    'selector' => 'h5'
                ]],
            ],
                [
                    'name' => 'test2',
                    'selectors' => [[
                        'alias' => 'test',
                        'selector' => 'h5'
                    ]],
                ]],
        ];

        return [
            [new Request([], $dataSet1), [], 200],
            [new Request([], $dataSet2), ['website_name' => 'Should be specified', 'website_url' => 'Should be specified', 'endpoints' => 'Should specify atleast one endpoint'], 400],
            [new Request([], $dataSet3), ['endpoints' => [["selectors" => "Should atleast specify one selector"]]], 400],
            [new Request([], $dataSet4), ['endpoints' => [["selectors" => [[
                'alias' => 'Cannot be empty and should be string!',
                'selector' => 'Cannot be empty and should be string!',
                'type' => 'Type should be one of the following: CSS,REGEX,XPATH',
            ]]]]], 400],
            [new Request([], $dataSet5), ['endpoints' => 'You can only test one endpoint at a time!'], 400],

        ];
    }

    /**
     * @dataProvider updateProvider
     *
     * @param Request $request
     * @param array $expected
     * @param integer $code
     */
    public function testUpdate($request, $expected, $code)
    {
        /** @var Curl|\PHPUnit_Framework_MockObject_MockObject $sourceRetrievalMock */
        $sourceRetrievalMock = $this->createMock(Curl::class);
        $sourceRetrievalMock->expects($this->any())->method('retrieveSource')
            ->willReturn('<html><body><a class="test">link</a><br/><a class="test">link</a></body></html>');

        /** @var DatabaseServiceContainer|\PHPUnit_Framework_MockObject_MockObject $databaseMock */
        $databaseMock = $this->createMock(DatabaseServiceContainer::class);

        // website will be empty
        $websiteServiceMock = $this->createMock(WebsiteService::class);
        $websiteServiceMock->expects($this->any())->method('getOneByName')->willReturn(null);
        $endpointServiceMock = $this->createMock(EndPointService::class);
        $endpointServiceMock->expects($this->any())->method('getOneByName')->willReturn(null);

        /** @var DatabaseServiceContainer|\PHPUnit_Framework_MockObject_MockObject $databaseMock */
        $databaseMock->expects($this->any())->method('getWebsiteService')->willReturn($websiteServiceMock);
        $databaseMock->expects($this->any())->method('getEndpointService')->willReturn($endpointServiceMock);


        $controller = new RequestController(
            $databaseMock,
            $sourceRetrievalMock
        );

        $response = $controller->update('test', 'test', $request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals($code, $response->getStatusCode());
        $this->assertEquals($expected, json_decode($response->getContent(), true));
    }

    /**
     * @see RequestControllerTest::testUpdate()
     */
    public function updateProvider()
    {
        $dataSet1 = [
            'website_name' => 'test',
            'website_url' => 'test',
            'endpoints' => [[
                'name' => 'test',
                'selectors' => [[
                    'alias' => 'test',
                    'selector' => 'h5'
                ]],
            ]],
        ];

        $dataSet2 = [
            'website_name' => '',
            'website_url' => '',
            'endpoints' => [],
        ];

        $dataSet3 = [
            'website_name' => 'test',
            'website_url' => 'test',
            'endpoints' => [[
                'name' => 'test',

            ]],
        ];

        $dataSet4 = [
            'website_name' => 'test',
            'website_url' => 'test',
            'endpoints' => [[
                'name' => 'test',
                'selectors' => [[
                    'type' => 'wrong'
                ]],
            ]],
        ];

        $dataSet5 = [
            'website_name' => 'test',
            'website_url' => 'test',
            'endpoints' => [[
                'name' => 'test',
                'selectors' => [[
                    'alias' => 'test',
                    'selector' => 'h5'
                ]],
            ],
                [
                    'name' => 'test2',
                    'selectors' => [[
                        'alias' => 'test',
                        'selector' => 'h5'
                    ]],
                ]],
        ];

        return [
            [new Request([], $dataSet1), ['successfully updated route : test/test'], 200],
            [new Request([], $dataSet2), ['successfully updated route : test/test'], 200],
            [new Request([], $dataSet3), ['successfully updated route : test/test'], 200],
            [new Request([], $dataSet4), ['successfully updated route : test/test'], 200],
            [new Request([], $dataSet5), ['successfully updated route : test/test'], 200],
        ];
    }

    /**
     * @dataProvider deleteProvider
     *
     * @param $websiteExists
     * @param $endpointExists
     * @param $expectedResult
     * @param $code
     */
    public function testDelete($websiteExists, $endpointExists, $expectedResult, $code, $throwException)
    {
        /** @var Curl|\PHPUnit_Framework_MockObject_MockObject $sourceRetrievalMock */
        $sourceRetrievalMock = $this->createMock(Curl::class);

        /** @var DatabaseServiceContainer|\PHPUnit_Framework_MockObject_MockObject $databaseMock */
        $databaseMock = $this->createMock(DatabaseServiceContainer::class);

        $connection = $this
            ->getMockBuilder(Connection::class)
            ->setMethods(['beginTransaction', 'commit', 'rollBack'])
            ->disableOriginalConstructor()
            ->getMock();
        $connection->expects($this->any())->method('beginTransaction')->willReturn(true);
        $connection->expects($this->any())->method('commit')->willReturn(true);
        $connection->expects($this->any())->method('rollBack')->willReturn(true);

        $databaseMock->expects($this->any())->method('getConnection')->willReturn($connection);

        $entityManager = $this->createMock(EntityManager::class);
        $entityManager->expects($this->any())->method('getConnection')->willReturn($connection);

        if ($throwException) {
            $entityManager->expects($this->any())->method('remove')->will($this->throwException(new \Exception('test_message')));
        }

        // website will be empty
        $websiteServiceMock = $this->getMockBuilder(WebsiteService::class)
            ->setMethods(['getEntityManager', 'getOneByName'])
            ->disableOriginalConstructor()
            ->getMock();
        $websiteServiceMock->expects($this->any())->method('getEntityManager')->willReturn($entityManager);

        if ($websiteExists) {
            $website = $this->createMock(Website::class);
            $websiteServiceMock->expects($this->any())->method('getOneByName')->willReturn($website);
        } else {
            $websiteServiceMock->expects($this->any())->method('getOneByName')->willReturn(null);
        }
        $endpointServiceMock = $this->createMock(EndPointService::class);

        if ($endpointExists) {

            $selector = $this->createMock(Selector::class);
            $selector->expects($this->any())->method('getOptions')->willReturn(
                [$this->createMock(SelectorOption::class)]
            );

            $endpoint = $this->createMock(Endpoint::class);
            $endpoint->expects($this->any())->method('getSelectors')->willReturn(
                [$selector]);
            $endpointServiceMock->expects($this->any())->method('getOneByName')->willReturn($endpoint);
        } else {
            $endpointServiceMock->expects($this->any())->method('getOneByName')->willReturn(null);
        }

        /** @var DatabaseServiceContainer|\PHPUnit_Framework_MockObject_MockObject $databaseMock */
        $databaseMock->expects($this->any())->method('getWebsiteService')->willReturn($websiteServiceMock);
        $databaseMock->expects($this->any())->method('getEndpointService')->willReturn($endpointServiceMock);


        $controller = new RequestController(
            $databaseMock,
            $sourceRetrievalMock
        );

        $response = $controller->delete('test', 'test');

        $this->assertEquals($expectedResult, json_decode($response->getContent(), true));
        $this->assertEquals($code, $response->getStatusCode());
    }

    public function deleteProvider()
    {
        return [
            [false, false, ["No endpoint found for route: test/test"], 404, false],
            [true, false,  ["No endpoint found for route: test/test"], 404, false],
            [false, true,  ["No endpoint found for route: test/test"], 404, false],
            [true, true,   ["successfully deleted route with name: test/test"], 200, false],
            [true, true,   ["Failed to delete route with name: test/test: test_message"], 500, true],
        ];
    }
}