<?php 

namespace LogCleaner\Controller;

use LogCleaner\LogProcessor\LogProcessorInterface;

interface ControllerInterface {

    public function run(LogProcessorInterface $processor, array $commandArray = []): mixed;
    
}