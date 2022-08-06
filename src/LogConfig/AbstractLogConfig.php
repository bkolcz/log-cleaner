<?php 

namespace LogCleaner\LogConfig;

use LogCleaner\Tests\LogConfig\LogConfigInterfaceTest;

abstract class AbstractLogConfig extends LogConfigInterfaceTest {
    private $configData;
    public function __construct(string $jsonData)
    {
        $this->configData = json_decode($jsonData, true);
    }

    public function getConfig(): mixed
    {
        return $this->configData;
    }

    public function __set($name, $value)
    {
        $this->configData[$name] = $value;
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->configData)) {
            return $this->configData[$name];
        }

        $trace = debug_backtrace();
        trigger_error(
            'Undefined property via __get(): ' . $name .
            ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line'],
            E_USER_NOTICE);
        return null;
    }

    public function __toString()
    {
        return serialize($this->configData);
    }
}