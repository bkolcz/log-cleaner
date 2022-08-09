<?php

namespace LogCleaner\Controller;

use LogCleaner\LogProcessor\LogProcessorInterface;

/**
 * This class controls LogProcessor
 */
class LogProcessorController implements ControllerInterface
{
    public function run(LogProcessorInterface $processor, array $commandArray = []): mixed
    {
        $response = [];
        foreach ($commandArray as $command => $body) {
            if (!array_key_exists($command, $response)) {
                $response[$command] = [];
            }
            switch ($command) {
                case 'remove':
                    array_push($response[$command], $processor->remove($body));
                    break;
                case 'removeAll':
                    array_push($response[$command], $processor->removeAll($body));
                    break;
                case 'analyse':
                    array_push($response[$command], $processor->analyse($body));
                    break;
                default:
                    array_push($response[$command], "Command not found...");
                    break;
            }
        }
        return $response;
    }
}
