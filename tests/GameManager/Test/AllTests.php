<?php

namespace GameManager\Test;

use GameManager\Test\Core\AllTestsCore;

class AllTests
{
    public static function main()
    {
        \PHPUnit_TextUI_TestRunner::run(self::suite());
    }
    
    public static function suite()
    {
        $suite = new \PHPUnit_Framework_TestSuite('GameManager');
        
        $suite->addTestSuite(AllTestsCore::suite());
        
        return $suite;
    }
}