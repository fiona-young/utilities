<?php

namespace Solarwinds\Oauth2\Test;

class TestCase extends \PHPUnit_Framework_TestCase
{

    public function getActualMock(
        $originalClassName,
        $methods = array(),
        array $arguments = array(),
        $mockClassName = '',
        $callOriginalConstructor = false,
        $callOriginalClone = true,
        $callAutoload = true,
        $cloneArguments = false,
        $callOriginalMethods = false
    ) {
        return $this->getMock($originalClassName, $methods, $arguments, $mockClassName, $callOriginalConstructor,
            $callOriginalClone, $callAutoload, $cloneArguments, $callOriginalMethods);
    }
}