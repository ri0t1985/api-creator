<?php

declare(strict_types=1);

namespace PhpUnit\App\Helpers;

use App\Entities\Selector;
use App\Helpers\HtmlParser;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Helpers\HtmlParser
 */
final class HtmlParserTest extends TestCase
{
    /**
     * @dataProvider parseCssProvider
     *
     * @covers \App\Helpers\HtmlParser::parse()
     *
     * @param Selector[] $selectors
     * @param string     $source
     * @param string[]   $expected
     */
    public function testCssParse(array $selectors, $source, $expected)
    {
        $htmlParser = new HtmlParser();
        $return =  $htmlParser->parse($selectors, $source);

        $this->assertEquals($expected, $return);
    }

    /**
     * @dataProvider parseXpathProvider
     *
     * @covers \App\Helpers\HtmlParser::parse()
     *
     * @param Selector[] $selectors
     * @param string     $source
     * @param string[]   $expected
     */
    public function testXpathParse(array $selectors, $source, $expected)
    {
        $htmlParser = new HtmlParser();
        $return =  $htmlParser->parse($selectors, $source);

        $this->assertEquals($expected, $return);
    }

    /**
     * @dataProvider parseRegexProvider
     *
     * @covers \App\Helpers\HtmlParser::parse()
     *
     * @param Selector[] $selectors
     * @param string     $source
     * @param string[]   $expected
     */
    public function testRegexParse(array $selectors, $source, $expected)
    {
        $htmlParser = new HtmlParser();
        $return =  $htmlParser->parse($selectors, $source);

        $this->assertEquals($expected, $return);
    }

    /**
     * @dataProvider exceptionProvider
     *
     * @param Selector[] $selectors
     * @param string $html
     * @param string $message
     * @param string $exceptionClass
     */
    public function testException(array $selectors, $html, $message, $exceptionClass)
    {
        if ($exceptionClass !== false)
        {
            $this->expectException($exceptionClass);
        }
        $htmlParser = new HtmlParser();
        $response = $htmlParser->parse($selectors, $html);

        if (false === $message)
        {
            $this->assertTrue(is_array($response));
        }
        else {
            $this->expectExceptionMessage($message);
        }
    }

    /**
     * @see HtmlParserTest::testCssParse()
     *
     * @return array
     */
    public function parseCssProvider()
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

    /**
     * @see HtmlParserTest::testXpathParse()
     *
     * @return array
     */
    public function parseXpathProvider()
    {
        $html_one =   '<html></html>';
        $html_two =   '<html><body></body></html>';
        $html_three = '<html><body><img src="test.jpg"/></body></html>';
        $html_four =  '<html><body><img class="test" src="test.jpg"/></body></html>';
        $html_five =  '<html><body><img class="test" src="test.jpg"/><br/><img class="test" src="test2.jpg"/></body></html>';

        $xpathSelector1 = new Selector();
        $xpathSelector1->setType(Selector::TYPE_XPATH)
            ->setAlias('xpath_1')
            ->setSelector('html/body/img[@class="test"]');

        $xpathSelector2 = new Selector();
        $xpathSelector2->setType(Selector::TYPE_XPATH)
            ->setAlias('xpath_2')
            ->setSelector('html/body/b');


        return [
            [[], $html_one, []],
            [[], $html_two, []],
            [[], $html_three, []],
            [[], $html_four, []],
            [[], $html_five, []],

            [[$xpathSelector1, $xpathSelector2], $html_one,   []],
            [[$xpathSelector1, $xpathSelector2], $html_two,   []],
            [[$xpathSelector1, $xpathSelector2], $html_three, []],
            [[$xpathSelector1, $xpathSelector2], $html_four,  [['xpath_1' => 'test.jpg']]],
            [[$xpathSelector1, $xpathSelector2], $html_five,  [['xpath_1' => 'test.jpg'], ['xpath_1' => 'test2.jpg']]],
        ];
    }

    /**
     * @see HtmlParserTest::testRegexParse()
     *
     * @return array
     */
    public function parseRegexProvider()
    {
        $html_one =   '<html></html>';
        $html_two =   '<html><body></body></html>';
        $html_three = '<html><body><a>link</a></body></html>';
        $html_four =  '<html><body><a class="test">link</a></body></html>';
        $html_five =  '<html><body><a class="test">link</a><br/><a class="test">link2</a></body></html>';


        $xpathSelector1 = new Selector();
        $xpathSelector1->setType(Selector::TYPE_REGEX)
            ->setAlias('regex_1')
            ->setSelector('class="test">([^<]*)<\/a>');

        $xpathSelector2 = new Selector();
        $xpathSelector2->setType(Selector::TYPE_REGEX)
            ->setAlias('regex_2')
            ->setSelector('b>(.*)<\/b>');


        return [
            [[], $html_one, []],
            [[], $html_two, []],
            [[], $html_three, []],
            [[], $html_four, []],
            [[], $html_five, []],

            [[$xpathSelector1, $xpathSelector2], $html_one,   []],
            [[$xpathSelector1, $xpathSelector2], $html_two,   []],
            [[$xpathSelector1, $xpathSelector2], $html_three, []],
            [[$xpathSelector1, $xpathSelector2], $html_four,  [['regex_1' => 'link']]],
            [[$xpathSelector1, $xpathSelector2], $html_five,  [['regex_1' => 'link'], ['regex_1' => 'link2']]],
        ];
    }

    /**
     * DataProvider for testException
     *
     * @see HtmlParserTest::testException()
     *
     * @return array
     */
    public function exceptionProvider()
    {
        $cssSelector = new Selector();
        $cssSelector->setType(Selector::TYPE_CSS)
            ->setAlias('css_1')
            ->setSelector('a.test');

        $invalidSelector = new InvalidSelector();

        return [

            [[],                               '',   'Unable to parse HTML!', \Exception::class],
            [[$cssSelector],                   '',   'Unable to parse HTML!', \Exception::class],
            [[$cssSelector, $invalidSelector], '',   'Unable to parse HTML!', \Exception::class],
            [[$invalidSelector],               '',   'Unable to parse HTML!', \Exception::class],

            [[],                                'asdfasdfasfd',  false, false],
            [[$cssSelector],                    'asdfasdfasfd',  false, false],
            [[$cssSelector, $invalidSelector],  'asdfasdfasfd', 'Invalid selector type supplied: INVALID!', \InvalidArgumentException::class],
            [[$invalidSelector],                'asdfasdfasfd', 'Invalid selector type supplied: INVALID',  \InvalidArgumentException::class],

            [[],                                 '<html>',       false,                   false],
            [[$cssSelector],                     '<html>',       false,                   false],
            [[$cssSelector, $invalidSelector],   '<html>',       'Unable to parse HTML!', \Exception::class],
            [[$invalidSelector],                 '<html>',       'Unable to parse HTML!', \Exception::class],
        ];
    }
}

class InvalidSelector extends Selector
{
    protected  $type = 'INVALID';
}