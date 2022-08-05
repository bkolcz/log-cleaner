<?php

namespace LogCleaner\LogConfig;

/**
 * This interface represents abstract configuration
 * for use in log processing
 */
interface LogConfigInterface
{
    /**
     * Constructor with use of json string
     * 
     * @param string $jsonData 
     * @return mixed 
     */
    public function __construct(string $jsonData);
    /** 
     * Method returns configuration
     * @return mixed  
     */
    public function getConfig(): mixed;
}
