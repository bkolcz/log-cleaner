<?php

namespace Bkolcz\LogCleaner\Tests\LogConfig;

use Bkolcz\LogCleaner\Tests\InterfaceTestInterface;
use PHPUnit\Framework\TestCase;

class LogConfigInterfaceTest extends TestCase implements InterfaceTestInterface
{

    public function testInterfacePresence()
    {
        $logConfigInterfacObject = $this->createMock(\Bkolcz\LogCleaner\LogConfig\LogConfigInterface::class);
        $this->assertTrue(!empty($logConfigInterfacObject));
    }

    public function testInterfaceMethods()
    {
        $this->markTestIncomplete("Not implemented test");
    }
}
