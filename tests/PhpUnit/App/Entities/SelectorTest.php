<?php

declare(strict_types=1);

namespace PhpUnit\App\Entities;

use App\Entities\Selector;
use PHPUnit\Framework\TestCase;

/**
 * @covers Selector
 */
final class SelectorTest extends TestCase
{
    /**
     * @param string $type
     * @param string $exceptionClass
     *
     * @dataProvider selectorProvider
     */
    public function testSelector($type, $exceptionClass): void
    {
        $selector = new Selector();

        $this->assertEmpty($selector->getType());
        $this->assertEmpty($selector->getSelector());
        $this->assertEmpty($selector->getAlias());
        $this->assertEmpty($selector->getEndpointId());

        if (false !== $exceptionClass)
        {
            $this->expectException($exceptionClass);
        }

        $selector->setType($type);

        $selector->setSelector('test_selector');
        $this->assertEquals('test_selector', $selector->getSelector());

        $selector->setAlias('test_alias');
        $this->assertEquals('test_alias', $selector->getAlias());
    }

    /**
     * DataProvider for SelectorTest::testSelector
     *
     * @return array
     */
    public function selectorProvider()
    {
        return [
            ['bla',                 \InvalidArgumentException::class],
            ['bloe',                \InvalidArgumentException::class],
            [null,                  \TypeError::class],
            [0,                     \TypeError::class],
            [false,                 \TypeError::class],
            [true,                  \TypeError::class],
            ['regex',               \InvalidArgumentException::class],
            ['css',                 \InvalidArgumentException::class],
            ['xpath',               \InvalidArgumentException::class],
            [Selector::TYPE_REGEX,  false],
            ['REGEX',               false],
            [Selector::TYPE_CSS,    false],
            ['CSS',                 false],
            [Selector::TYPE_XPATH,  false],
            ['XPATH',               false],
        ];
    }
}