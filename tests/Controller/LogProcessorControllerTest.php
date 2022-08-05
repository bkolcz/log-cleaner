<?php

namespace Bkolcz\LogCleaner\Tests\Controller;


use PHPUnit\Framework\TestCase;


class LogProcessorControllerTest extends TestCase
{

    public function testPresence()
    {
        $logProcessController = $this->createMock(\Bkolcz\LogCleaner\Controller\LogProcessorController::class);
        $this->assertTrue(!empty($logProcessController));
    }
    /**
     * @dataProvider methodProvider
     *
     * @param string $method
     * @return void
     */
    public function testMethod(string $method)
    {
        $mockedObject = $this->createMock(\Bkolcz\LogCleaner\Controller\LogProcessorController::class);
        $this->assertTrue(method_exists($mockedObject, $method), "Method [$method] not found");
    }

    public function methodProvider(): array
    {
        return [
            "run" => ["run"]
        ];
    }
}
