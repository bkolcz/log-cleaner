<?php

namespace Bkolcz\LogCleaner\Controller;

use Bkolcz\LogCleaner\LogProcessor\LogProcessorInterface;

/**
 * This class controls LogProcessor
 */
class LogProcessorController
{
    public function run(LogProcessorInterface $processor, array $commandArray = []): mixed
    {
        foreach ($commandArray as $command => $body) {
            switch ($command) {
                case 'remove':
                    echo $processor->remove($body);
                    break;
                case 'removeAll':
                    echo $processor->removeAll($body);
                    break;
                case 'analyse':
                    echo $processor->analyse($body);
                    break;
                default:
                    echo "Command not found...";
                    break;
            }
        }
    }

}