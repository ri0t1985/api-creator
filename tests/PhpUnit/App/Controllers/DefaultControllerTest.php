<?php

declare(strict_types=1);

namespace PhpUnit\App\Controllers;

use App\Controllers\DefaultController;
use PHPUnit\Framework\TestCase;

/**
 * @covers DefaultController
 */
final class DefaultControllerTest extends TestCase
{
    public function testSomething(): void
    {
        $this->markTestIncomplete();

    }
}