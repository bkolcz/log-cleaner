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
    public function testMethods()
    {
        $this->markTestIncomplete("Not implemented test");
    }
}
