<?php

declare(strict_types=1);

namespace PhpUnit\App\Entities;

use App\Entities\Selector;
use App\Entities\SelectorOption;
use PHPUnit\Framework\TestCase;

/**
 * @covers Selector
 */
final class SelectorOptionTest extends TestCase
{
    /**
     * @covers  SelectorOption::setKey()
     * @covers  SelectorOption::setValue()
     */
    public function testSelectorOption(): void
    {
        $selectorOption = new SelectorOption();

        $this->assertEmpty($selectorOption->getKey());
        $this->assertEmpty($selectorOption->getValue());
        $this->assertEmpty($selectorOption->getSelector());

        $selectorOption->setKey('test_key');
        $selectorOption->setValue('test_value');

        $this->assertEquals('test_key', $selectorOption->getKey());
        $this->assertEquals('test_value', $selectorOption->getValue());

    }
}