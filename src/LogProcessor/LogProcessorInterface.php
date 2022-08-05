<?php

namespace Bkolcz\LogCleaner\LogProcessor;

use Bkolcz\LogCleaner\LogConfig\LogConfigInterface;

/**
 * This interface represents log processor abstraction
 */
interface LogProcessorInterface
{

    public function __construct(LogConfigInterface $config);    
    /**
     * remove single element using config array
     *
     * @param array config
     *
     * @return mixed
     */
    public function remove(array $config = []): mixed;    
    /**
     * remove all elements matching config array
     *
     * @param array config
     *
     * @return mixed
     */
    public function removeAll(array $config = []): mixed;

        
    /**
     * analyse data using configuration array
     *
     * @param array config
     *
     * @return mixed
     */
    public function analyse(array $config = []): mixed;
}
