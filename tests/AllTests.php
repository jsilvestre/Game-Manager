<?php

require_once 'core/Core_AllTests.php';

class AllTests
{
    public static function main()
    {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }
    
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('GameManager');
        
        $suite->addTestSuite(Core_AllTests::suite());
        
        return $suite;
    }
}