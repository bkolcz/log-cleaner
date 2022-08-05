<?php 

namespace LogCleaner\LogProcessor;

use LogCleaner\LogConfig\LogConfigInterface;

class StandardDbLogProcessor extends AbstractDbLogProcessor {

    public function __construct(LogConfigInterface $config) { }

    public function remove(array $config = []): mixed { }

    public function removeAll(array $config = []): mixed { }

    public function analyse(array $config = []): mixed { }

}