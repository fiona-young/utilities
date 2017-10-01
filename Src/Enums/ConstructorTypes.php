<?php

namespace Matters\Utilities\Enums;

class ConstructorTypes
{

    private static $Initialized = false;
    private static $MAP = array();
    private static $ALL = array();

    private static $NONE;
    private static $ARRAY_IN;
    private static $PARAMETER_LIST;

    private $methodName;

    private function __construct($methodName)
    {
        $this->methodName = $methodName;
    }

    public function getMethodName()
    {
        return $this->methodName;
    }

    /**
     * @param $methodName
     * @return bool | ConstructorTypes
     */
    public static function findByMethodName($methodName)
    {
        if (isset(self::$MAP['methodName'][$methodName])) {
            return self::$MAP['methodName'][$methodName];
        }

        return false;
    }

    /** @return ConstructorTypes[] */
    public static function ALL()
    {
        return self::$ALL;
    }

    /** @return ConstructorTypes */
    public static function NONE()
    {
        return self::$NONE;
    }

    /** @return ConstructorTypes */
    public static function ARRAY_IN()
    {
        return self::$ARRAY_IN;
    }

    /** @return ConstructorTypes */
    public static function PARAMETER_LIST()
    {
        return self::$PARAMETER_LIST;
    }

    public static function initialize()
    {
        if (self::$Initialized) {
            return;
        }

        self::$NONE = new ConstructorTypes('NONE');
        self::$ARRAY_IN = new ConstructorTypes('ARRAY_IN');
        self::$PARAMETER_LIST = new ConstructorTypes('PARAMETER_LIST');

        self::$ALL = [
            self::$NONE,
            self::$ARRAY_IN,
            self::$PARAMETER_LIST
        ];

        self::initializeMaps();

        self::$Initialized = true;
    }

    private static function initializeMaps()
    {
        self::$MAP['methodName'] = [
            'NONE' => self::$NONE,
            'ARRAY_IN' => self::$ARRAY_IN,
            'PARAMETER_LIST' => self::$PARAMETER_LIST
        ];

    }
}

ConstructorTypes::initialize();
