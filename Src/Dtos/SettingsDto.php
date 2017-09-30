<?php

namespace Matters\Utilities\Dtos;

class SettingsDto
{

    private $data;

    public function __construct($data = [])
    {
        $this->data = (array)$data;
    }

    public function getDatabaseEngine($default = null)
    {
       return $this->getLevel2Property('database', 'engine', $default);
    }

    public function getDatabaseHost($default = null)
    {
       return $this->getLevel2Property('database', 'host', $default);
    }

    public function getDatabaseDatabase($default = null)
    {
       return $this->getLevel2Property('database', 'database', $default);
    }

    public function getDatabaseUsername($default = null)
    {
       return $this->getLevel2Property('database', 'username', $default);
    }

    public function getDatabasePassword($default = null)
    {
       return $this->getLevel2Property('database', 'password', $default);
    }

    private function getLevel2Property($key1, $key2, $default)
    {
        if (is_array($this->data[$key1]) && array_key_exists($key2, $this->data[$key1])) {
            return $this->data[$key1][$key2];
        } else {
            return $default;
        }
    }
}
