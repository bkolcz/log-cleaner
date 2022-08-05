<?php

namespace LogCleaner\LogConfig;

abstract class AbstractDbLogConfig implements LogConfigInterface
{

    public function __construct(string $jsonData)
    {
    }

    public function getConfig(): mixed
    {
    }
}
