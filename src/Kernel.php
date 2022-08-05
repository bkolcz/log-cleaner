<?php

namespace LogCleaner;

use LogCleaner\Controller\ControllerInterface;
use LogCleaner\Controller\LogProcessorController;
use LogCleaner\LogConfig\LogConfigInterface;
use LogCleaner\LogConfig\StandardDbLogConfig;
use LogCleaner\LogConfig\StandardFileLogConfig;
use LogCleaner\LogProcessor\LogProcessorInterface;
use LogCleaner\LogProcessor\StandardDbLogProcessor;
use LogCleaner\LogProcessor\StandardFileLogProcessor;

class Kernel
{

    private ControllerInterface $controller;
    private LogProcessorInterface $processor;

    private LogConfigInterface $config;
    public function __construct(private array $args = ['type' => 'standard', 'processor' => 'file', 'jsonData' => ''])
    {
        $this->args['type'] = array_key_exists('type', $this->args) ? $this->args['type'] : 'standard';
        $this->args['processor'] = array_key_exists('processor', $this->args) ? $this->args['processor'] : 'file';
        $this->args['jsonData'] = array_key_exists('jsonData', $this->args) ? $this->args['jsonData'] : '';
        $this->setJsonConfig()->setConfig()->setProcessor()->setController();
    }

    public function run(array $commandArray = []): mixed
    {
        return $this->controller->run($this->processor, $commandArray);
    }


    static public function getTemplateArgs(string $type = 'custom', string $processor = 'file' ): array
    {
        return [
            'type' => $type,
            'jsonData' => '',
            'processor' => $processor
        ];
    }

    public function setJsonConfig(): Kernel
    {
        $configDir = dirname(__DIR__);
        if (empty($this->args['jsonData'])) {
            $this->args['jsonData'] = match ($this->args['processor']) {
                'file' => file_get_contents($configDir . '/defaultFileConfig.json'),
                'db' => file_get_contents($configDir . '/defaultDbConfig.json'),
                default => ''
            };
        }
        return $this;
    }

    public function setConfig(): Kernel
    {
        $this->config = match ($this->args['type']) {
            'db' => new StandardDbLogConfig($this->args['jsonData']),
            default => new StandardFileLogConfig($this->args['jsonData'])
        };
        return $this;
    }

    public function setProcessor(): Kernel
    {
        $this->processor = match ($this->args['processor']) {
            'db' => new StandardDbLogProcessor($this->config),
            default => new StandardFileLogProcessor($this->config)
        };
        return $this;
    }

    public function setController(): Kernel
    {
        $this->controller = new LogProcessorController();
        return $this;
    }
}
