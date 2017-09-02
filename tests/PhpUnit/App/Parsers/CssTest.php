<?php

declare(strict_types=1);

namespace PhpUnit\App\Parsers;

use App\Parsers\Css;
use PHPUnit\Framework\TestCase;
use Sunra\PhpSimple\HtmlDomParser;

/**
 * @covers \App\Parsers\Css
 */
final class CssTest extends TestCase
{
    /**
     * @param string $html
     * @param string $selector
     * @param array $expected
     *
     * @covers \App\Parsers\Css
     *
     * @dataProvider processProvider
     */
    public function testProcess($html, $selector, $expected): void
    {
        $html = HtmlDomParser::str_get_html($html);
        $css = new Css($html);
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
     * @see \App\Parsers\Css::testProcess()
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
            [$html_one, '', []],
            [$html_two, '', []],
            [$html_three, '', []],
            [$html_four, '', []],
            [$html_five, '', []],

            [$html_one, 'a', []],
            [$html_two, 'a', []],
            [$html_three, 'a', ['<a>link</a>']],
            [$html_four, 'a',  ['<a class="test">link</a>']],
            [$html_five, 'a',  ['<a class="test">link</a>','<a class="test">link</a>']],

            [$html_one, 'a.test', []],
            [$html_two, 'a.test', []],
            [$html_three, 'a.test', []],
            [$html_four, 'a.test', ['<a class="test">link</a>']],
            [$html_five, 'a.test', ['<a class="test">link</a>','<a class="test">link</a>']],

            [$html_one, 'body a.test', []],
            [$html_two, 'body a.test', []],
            [$html_three, 'body a.test', []],
            [$html_four, 'body a.test', ['<a class="test">link</a>']],
            [$html_five, 'body a.test', ['<a class="test">link</a>','<a class="test">link</a>']],

            [$html_one, 'html body a.test', []],
            [$html_two, 'html body a.test', []],
            [$html_three, 'html body a.test', []],
            [$html_four, 'html body a.test', ['<a class="test">link</a>']],
            [$html_five, 'html body a.test', ['<a class="test">link</a>','<a class="test">link</a>']],
        ];
    }
}