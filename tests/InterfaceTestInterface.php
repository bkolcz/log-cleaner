<?php

namespace LogCleaner\Tests;

/**
 * This interface delivers must have methods in interface testing
 */
interface InterfaceTestInterface
{

    /**
     * This method test if there is such interface 
     * 
     * @return void
     */
    public function testInterfacePresence();

    /**
     * This method test if interface keeps desired implementation
     *
     * @return void
     */
    public function testInterfaceMethod(string $method);

    /**
     * This method provides data for testInterfaceMethod
     * 
     * @return array 
     */
    public function methodProvider(): array;
}
