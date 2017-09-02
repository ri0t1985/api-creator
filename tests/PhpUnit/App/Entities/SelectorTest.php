<?php

declare(strict_types=1);

namespace PhpUnit\App\Entities;

use App\Entities\Endpoint;
use App\Entities\Selector;
use App\Entities\SelectorOption;
use Doctrine\ORM\Query\Expr\Select;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Entities\Selector
 */
final class SelectorTest extends TestCase
{
    /**
     * @param string $type
     * @param string $exceptionClass
     *
     * @covers \App\Entities\Selector
     *
     * @dataProvider selectorProvider
     */
    public function testSelector($type, $exceptionClass)
    {
        $selector = new Selector();

        $this->assertEmpty($selector->getId());
        $this->assertEmpty($selector->getType());
        $this->assertEmpty($selector->getSelector());
        $this->assertEmpty($selector->getAlias());
        $this->assertEmpty($selector->getEndpointId());
        $this->assertEmpty($selector->getOptions());

        if (false !== $exceptionClass)
        {
            $this->expectException($exceptionClass);
        }

        $selector->setType($type);

        $selector->setSelector('test_selector');
        $this->assertEquals('test_selector', $selector->getSelector());

        $selector->setAlias('test_alias');
        $this->assertEquals('test_alias', $selector->getAlias());

        $endpointMock = $this->createMock(Endpoint::class);
        $endpointMock->expects($this->any())->method('getId')->willReturn('123456');

        $selector->setEndpoint($endpointMock);

        $selector = $selector->getEndPoint();

        $this->assertEquals('123456', $selector->getId());
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

    /**
     * @dataProvider optionsDataProvider
     *
     * @param string $option
     */
    public function testOptions($option)
    {
        $selector = new Selector();

        $this->assertEmpty($selector->getOptions());
        $this->assertFalse($selector->hasOption($option));
        $this->assertNull($selector->getOption($option));
        $this->assertNull($selector->getOption($option, null));
        $this->assertFalse($selector->getOption($option, false));
        $this->assertTrue($selector->getOption($option, true));

        $selector->setOption($option, 'test');
        $this->assertEquals('test', $selector->getOption($option));
        $this->assertTrue($selector->hasOption($option));
    }

    /**
     * @return array
     */
    public function optionsDataProvider()
    {
        return
        [
            [SelectorOption::OPTION_TRIM],
            [SelectorOption::OPTION_STRIP_HTML],
            [SelectorOption::OPTION_PROPERTY],
        ];
    }

    public function testOptionsAsArray()
    {
        $option1 = new SelectorOption();
        $option1->setKey('testkey')
            ->setValue('testvalue');

        $option2 = new SelectorOption();
        $option2->setKey('testkey_2')
            ->setValue('testvalue_2');

        /** @var \PHPUnit_Framework_MockObject_MockObject|Selector $mock */
         $mock = $this->getMockBuilder(Selector::class)
            ->setMethods(array('getOptions'))
            ->getMock();
         $mock->expects(($this->any()))->method('getOptions')->willReturn([$option1, $option2]);

        $this->assertTrue($mock->hasOption('testkey'));
        $this->assertTrue($mock->hasOption('testkey_2'));
        $this->assertFalse($mock->hasOption('testkey_3'));

        $this->assertEquals('testvalue', $mock->getOption('testkey'));
        $this->assertEquals('testvalue_2', $mock->getOption('testkey_2'));
    }
}