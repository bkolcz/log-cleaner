<?php

namespace LogCleaner\LogConfig;

abstract class AbstractFileLogConfig implements LogConfigInterface
{

    public function __construct(string $jsonData)
    {
    }

    public function getConfig(): mixed
    {
    }
}
