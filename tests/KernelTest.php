<?php

use LogCleaner\Controller\LogProcessorController;
use LogCleaner\Kernel;
use LogCleaner\LogProcessor\StandardFileLogProcessor;
use LogCleaner\LogConfig\StandardFileLogConfig;
use PHPUnit\Framework\TestCase;

class KernelTest extends TestCase
{

    public function testKernelObject()
    {
        $kernelArgs = Kernel::getTemplateArgs(type: "standard");
        $kernel = new Kernel($kernelArgs);

        $this->assertInstanceOf(StandardFileLogProcessor::class, $kernel->getProcessor());
        $this->assertInstanceOf(StandardFileLogConfig::class, $kernel->getConfig());
        $this->assertInstanceOf(LogProcessorController::class, $kernel->getController());
    }

    public function testKernelArgs()
    {
        $kernelArgs = Kernel::getTemplateArgs();
        $expectedArray = [
            "type" => "custom",
            "jsonData" => "",
            "processor" => "file"
        ];

        $this->assertIsArray($kernelArgs);
        $this->assertEquals($expectedArray, $kernelArgs);
    }

    public function testRunKernel()
    {
        $path = dirname(__DIR__) . "/sample/sample-access.log";
        $sampleFile = realpath($path);
        $kernelArgs = Kernel::getTemplateArgs(type: "standard");
        $kernel = new Kernel($kernelArgs);
        $this->expectNotice();
        $status = $kernel->run(["removeAll" => [
            "inputFile" => $sampleFile,
            "outputFile" => null,
            "stdOut" => null,
            "dateFrom" => new DateTime("24/Mar/2022:13:59:20 +0100"),
            "dateTo" => null,
            "spaceRegex" => null,
            "spaceRegexRevert" => null,
            "spaceMockCharacter" => null,
            "delimiter" => null,
            "enclosure" => null,
            "replaceDateChars" => [
                "from" => [
                    "[",
                    "]",
                    "_"
                ],
                "to" => " "
            ],
            "dateColumnIndex" => -1
        ]]);
        $this->assertArrayHasKey("removeAll", $status);
        $this->assertCount(2, $status["removeAll"]);
        $this->assertNotEmpty(realpath($path . "_tmp.log"));
    }
}
