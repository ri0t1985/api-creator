<?php

declare(strict_types=1);

namespace PhpUnit\App\Parsers;

use App\Parsers\Xpath;
use PHPUnit\Framework\TestCase;
use Sunra\PhpSimple\HtmlDomParser;

/**
 * @covers \App\Parsers\Xpath
 */
final class XpathTest extends TestCase
{
    /**
     * @param string $html
     * @param string $selector
     * @param array $expected
     *
     * @covers \App\Parsers\Xpath
     *
     * @dataProvider processProvider
     */
    public function testProcess($html, $selector, $expected): void
    {
        $html = HtmlDomParser::str_get_html($html);
        $css = new Xpath($html);
        $response = $css->process($selector);

        $this->assertTrue(is_array($response));


        if (is_array($response))
        {
            $this->assertCount(count($expected), $response);

            foreach ($response as $key => $value)
            {
                $this->assertEquals($expected[$key], (string)$value);
            }
        }
    }

    /**
     * @see \App\Parsers\Xpath::testProcess()
     *
     * @return array
     */
    public function processProvider()
    {
        $html_one =   '<html></html>';
        $html_two =   '<html><body></body></html>';
        $html_three = '<html><body><a>link</a></body></html>';
        $html_four =  '<html><body><a class="test">link</a></body></html>';
        $html_five =  '<html><body><a class="test">link</a><br/><a class="test">link</a></body></html>';

        return [
            // should all be empty
            [$html_one,   '', []],
            [$html_two,   '', []],
            [$html_three, '', []],
            [$html_four,  '', []],
            [$html_five,  '', []],

            [$html_one,   'html/body/a', []],
            [$html_two,   'html/body/a', []],
            [$html_three, 'html/body/a', ['<a>link</a>']],
            [$html_four,  'html/body/a', ['<a class="test">link</a>']],
            [$html_five,  'html/body/a', ['<a class="test">link</a>','<a class="test">link</a>']],

            [$html_one,   'html/body/a[@class="test"]', []],
            [$html_two,   'html/body/a[@class="test"]', []],
            [$html_three, 'html/body/a[@class="test"]', []],
            [$html_four,  'html/body/a[@class="test"]', ['<a class="test">link</a>']],
            [$html_five,  'html/body/a[@class="test"]', ['<a class="test">link</a>','<a class="test">link</a>']],

            [$html_one,   '//a[@Class="test"]', []],
            [$html_two,   '//a[@Class="test"]', []],
            [$html_three, '//a[@Class="test"]', []],
            [$html_four,  '//a[@Class="test"]', ['<a class="test">link</a>']],
            [$html_five,  '//a[@Class="test"]', ['<a class="test">link</a>','<a class="test">link</a>']],
        ];
    }
}