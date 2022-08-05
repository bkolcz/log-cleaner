<?php

namespace LogCleaner\Tests\LogProcessor;

use LogCleaner\Tests\InterfaceTestInterface;
use PHPUnit\Framework\TestCase;

class LogProcessorInterfaceTest extends TestCase implements InterfaceTestInterface
{

    public function testInterfacePresence()
    {
        $interfaceObject = $this->createMock(\LogCleaner\LogProcessor\LogProcessorInterface::class);
        $this->assertTrue(!empty($interfaceObject));
    }

    /** 
     * @dataProvider methodProvider
     */
    public function testInterfaceMethod(string $method)
    {
        $interfaceObject = $this->createMock(\LogCleaner\LogProcessor\LogProcessorInterface::class);
        $this->assertTrue(method_exists($interfaceObject, $method), "Method [$method] not found");
    }

    public function methodProvider(): array
    {
        return [
            "remove" => ["remove"],
            "removeAll" => ["removeAll"],
            "analyse" => ["analyse"]
        ];
    }
}
