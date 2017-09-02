<?php

declare(strict_types=1);

namespace PhpUnit\App\Parsers;

use App\Parsers\Regex;
use PHPUnit\Framework\TestCase;
use Sunra\PhpSimple\HtmlDomParser;

/**
 * @covers \App\Parsers\Regex
 */
final class RegexTest extends TestCase
{
    /**
     * @param string $html
     * @param string $selector
     * @param array $expected
     *
     * @covers \App\Parsers\Regex
     *
     * @dataProvider processProvider
     */
    public function testProcess($html, $selector, $expected)
    {
        $html = HtmlDomParser::str_get_html($html);
        $css = new Regex($html);
        $response = $css->process($selector);

        $this->assertTrue(is_array($response));
        $this->assertCount(count($expected), $response);

        foreach ($response as $key => $value)
        {
            $this->assertEquals($expected[$key], (string)$value);
        }
    }

    /**
     * @see CssTest::testProcess()
     *
     * @return array
     */
    public function processProvider()
    {
        $html_one   =  '<html></html>';
        $html_two   =  '<html><body></body></html>';
        $html_three =  '<html><body><a>link</a></body></html>';
        $html_four  =  '<html><body><a class="test">link</a></body></html>';
        $html_five  =  '<html><body><a class="test">link</a><br/><a class="test">link</a></body></html>';
        $html_six   =  '<html><body><a class="test">link</a><br/><a>link</a></body></html>';

        return [
            // should all be empty
            [$html_one,   '', []],
            [$html_two,   '', []],
            [$html_three, '', []],
            [$html_four,  '', []],
            [$html_five,  '', []],
            [$html_six,   '', []],

            [$html_one,   '<a>(.*?)<\/a>', []],
            [$html_two,   '<a>(.*?)<\/a>', []],
            [$html_three, '<a>(.*?)<\/a>', ['link']],
            [$html_four,  '<a>(.*?)<\/a>',  []],
            [$html_five,  '<a>(.*?)<\/a>',  []],
            [$html_five,  '<a>(.*?)<\/a>',  []],

            [$html_one,   'class="test">(.*)<\/a>', []],
            [$html_two,   'class="test">(.*)<\/a>', []],
            [$html_three, 'class="test">(.*)<\/a>', []],
            [$html_four,  'class="test">(.*)<\/a>', ['link']],
            [$html_five,  'class="test">(.*)<\/a>', ['link</a><br/><a class="test">link']],
            [$html_six,   'class="test">(.*)<\/a>', ['link</a><br/><a>link']],

            [$html_one,   'class="test">([^<]*)<\/a>', []],
            [$html_two,   'class="test">([^<]*)<\/a>', []],
            [$html_three, 'class="test">([^<]*)<\/a>', []],
            [$html_four,  'class="test">([^<]*)<\/a>', ['link']],
            [$html_five,  'class="test">([^<]*)<\/a>', ['link', 'link']],
            [$html_six,   'class="test">([^<]*)<\/a>', ['link']],
        ];
    }
}