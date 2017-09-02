<?php

declare(strict_types=1);

namespace PhpUnit\App\Controllers;

use App\Controllers\DefaultController;
use App\Entities\Endpoint;
use App\Entities\Selector;
use App\Entities\Website;
use App\Services\DatabaseServiceContainer;
use App\SourceRetrieval\Curl;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @covers \App\Controllers\DefaultController
 */
final class DefaultControllerTest extends TestCase
{
    /**
     * @dataProvider processEndpointProvider
     *
     * @covers \App\Controllers\DefaultController::processEndPoint()
     *
     * @param $selectors
     * @param $html
     * @param $expected
     */
    public function testProcessEndpoint($selectors, $html, $expected)
    {
        /** @var Curl|\PHPUnit_Framework_MockObject_MockObject  $sourceRetrievalMock */
        $sourceRetrievalMock = $this->createMock(Curl::class);
        $sourceRetrievalMock->expects($this->any())
            ->method('retrieveSource')
            ->willReturn($html);

        /** @var DatabaseServiceContainer|\PHPUnit_Framework_MockObject_MockObject  $databaseMock */
        $databaseMock = $this->createMock(DatabaseServiceContainer::class);

        $controller = new DefaultController(
            $databaseMock,
            $sourceRetrievalMock
        );

        $website = new Website();
        $website
            ->setUrl('test.com')
            ->setName('test');
        $endpoint = new Endpoint();
        $endpoint
            ->setName('test')
            ->setSelectors($selectors);

        $response = $controller->processEndPoint($website, $endpoint);

        $this->assertInstanceOf(
            JsonResponse::class,
            $response,
            'Expected to receive a JSON response, got something else instead'
        );

        $expected = new JsonResponse($expected);
        $this->assertEquals(
            $expected,
            $response,
            'Expected to receive a JSON response, got something else instead'
        );
    }


    /**
     * DataProvider for DefaultControllerTest::testProcessEndpoint
     *
     * @return array
     */
    public function processEndpointProvider()
    {
        $html_one =   '<html></html>';
        $html_two =   '<html><body></body></html>';
        $html_three = '<html><body><a>link</a></body></html>';
        $html_four =  '<html><body><a class="test">link</a></body></html>';
        $html_five =  '<html><body><a class="test">link</a><br/><a class="test">link2</a></body></html>';


        $cssSelector1 = new Selector();
        $cssSelector1->setType(Selector::TYPE_CSS)
            ->setAlias('css_1')
            ->setSelector('a.test');

        $cssSelector2 = new Selector();
        $cssSelector2->setType(Selector::TYPE_CSS)
            ->setAlias('css_2')
            ->setSelector('b.test');


        return [
            [[], $html_one, []],
            [[], $html_two, []],
            [[], $html_three, []],
            [[], $html_four, []],
            [[], $html_five, []],

            [[$cssSelector1, $cssSelector2], $html_one,   []],
            [[$cssSelector1, $cssSelector2], $html_two,   []],
            [[$cssSelector1, $cssSelector2], $html_three, []],
            [[$cssSelector1, $cssSelector2], $html_four,  [['css_1' => 'link']]],
            [[$cssSelector1, $cssSelector2], $html_five,  [['css_1' => 'link'], ['css_1' => 'link2']]],
        ];
    }
}