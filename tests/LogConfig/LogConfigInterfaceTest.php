<?php

namespace Bkolcz\LogCleaner\Tests\LogConfig;

use Bkolcz\LogCleaner\Tests\InterfaceTestInterface;
use PHPUnit\Framework\TestCase;

class LogConfigInterfaceTest extends TestCase implements InterfaceTestInterface
{

    public function methodProvider(): array
    {
        return [
            "getConfig" => ["getConfig"]
        ];
    }

    public function testInterfacePresence()
    {
        $interfaceObject = $this->createMock(\Bkolcz\LogCleaner\LogConfig\LogConfigInterface::class);
        $this->assertTrue(!empty($interfaceObject));
    }

    /** 
     * @dataProvider methodProvider
     */
    public function testInterfaceMethod(string $method)
    {
        $interfaceObject = $this->createMock(\Bkolcz\LogCleaner\LogConfig\LogConfigInterface::class);
        $this->assertTrue(method_exists($interfaceObject, $method), "Method [$method] not found");
    }
}
