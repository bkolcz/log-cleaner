<?php

namespace Bkolcz\LogCleaner\Tests\LogConfig;

use Bkolcz\LogCleaner\Tests\InterfaceTestInterface;
use PHPUnit\Framework\TestCase;

class LogConfigInterfaceTest extends TestCase implements InterfaceTestInterface
{

    public function testInterfacePresence()
    {
        $this->createMock(Bkolcz\LogCleaner\LogConfig\LogConfigInterface::class);
    }

    public function testInterfaceMethods()
    {
        $this->markTestIncomplete("Not implemented test");
    }
}
