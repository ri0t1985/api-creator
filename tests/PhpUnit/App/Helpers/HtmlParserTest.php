<?php

declare(strict_types=1);

namespace PhpUnit\App\Helpers;

use App\Entities\Selector;
use App\Helpers\HtmlParser;
use PHPUnit\Framework\TestCase;

/**
 * @covers HtmlParser
 */
final class HtmlParserTest extends TestCase
{
    /**
     * @dataProvider parseProvider
     */
    public function testParse($selectors, $source, $expected): void
    {
        $htmlParser = new HtmlParser();
        $return =  $htmlParser->parse($selectors, $source);

        $this->assertEquals($expected, $return);
    }


    public function parseProvider()
    {
        return [
            [[], null, []]
        ];

    }

}