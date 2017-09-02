<?php

declare(strict_types=1);

namespace PhpUnit\App\Entities;

use App\Entities\Selector;
use App\Entities\SelectorOption;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Entities\SelectorOption
 */
final class SelectorOptionTest extends TestCase
{
    /**
     * @covers \App\Entities\SelectorOption
     */
    public function testSelectorOption(): void
    {
        $selectorOption = new SelectorOption();

        $this->assertEmpty($selectorOption->getKey());
        $this->assertEmpty($selectorOption->getId());
        $this->assertEmpty($selectorOption->getValue());
        $this->assertEmpty($selectorOption->getSelector());

        $selectorOption->setKey('test_key');
        $selectorOption->setValue('test_value');

        $this->assertEquals('test_key', $selectorOption->getKey());
        $this->assertEquals('test_value', $selectorOption->getValue());

        $selectorMock = $this->createMock(Selector::class);
        $selectorMock->expects($this->any())->method('getId')->willReturn('123456');

        $selectorOption->setSelector($selectorMock);

        $selector = $selectorOption->getSelector();

        $this->assertEquals('123456', $selector->getId());

    }
}