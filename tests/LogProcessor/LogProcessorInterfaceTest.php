<?php

namespace Bkolcz\LogCleaner\Tests\LogProcessor;

use Bkolcz\LogCleaner\Tests\InterfaceTestInterface;
use PHPUnit\Framework\TestCase;

class LogProcessorInterfaceTest extends TestCase implements InterfaceTestInterface
{

    public function testInterfacePresence()
    {
        $logProcessorInterfaceObject = $this->createMock(\Bkolcz\LogCleaner\LogProcessor\LogProcessorInterface::class);
        $this->assertTrue(!empty($logProcessorInterfaceObject));
    }

    public function testInterfaceMethods()
    {
        $this->markTestIncomplete("Not implemented test");
    }
}
