<?php 

namespace Bkolcz\LogCleaner\LogConfig;
/**
 * This interface represents abstract configuration
 * for use in log processing
 */
interface LogConfigInterface {

    /** 
     * Method returns configuration
     * @return mixed  
     */
    public function getConfig() : mixed;
}