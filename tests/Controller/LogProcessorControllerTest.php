<?php

namespace Bkolcz\LogCleaner\Tests\Controller;

use Bkolcz\LogCleaner\Tests\InterfaceTestInterface;
use PHPUnit\Framework\TestCase;


class LogProcessorControllerTest extends TestCase
{

    public function testPresence()
    {
        $this->createMock(Bkolcz\LogCleaner\Controller\LogProcessorController::class);
    }
    public function testMethods()
    {
        $this->markTestIncomplete("Not implemented test");
    }
}
