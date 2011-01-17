<?php

namespace GameManager\Test\Core;

class AllTestsCore
{
    public static function main()
    {
        \PHPUnit_TextUI_TestRunner::run(self::suite());
    }
    
    public static function suite()
    {
        $suite = new \PHPUnit_Framework_TestSuite('core');
        
        $suite->addTestSuite('GameManager\Test\Core\Library\TestRouter');
        $suite->addTestSuite('GameManager\Test\Core\Library\TestRoute');
        $suite->addTestSuite('GameManager\Test\Core\Component\TestRequest');
        $suite->addTestSuite('GameManager\Test\Core\Component\TestCollection');
        
        return $suite;
    }
}