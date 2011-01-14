<?php

include 'TestRouter.php';
include 'TestRoute.php';
include 'TestRequest.php';

class Core_AllTests
{
    public static function main()
    {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }
    
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('core');
        
        $suite->addTestSuite('TestRouter');
        $suite->addTestSuite('TestRoute');
        $suite->addTestSuite('TestRequest');
        
        return $suite;
    }
}