<?php

namespace Matters\Utilities\Dtos;

class SettingsDto
{

    private $data;

    public function __construct($data = [])
    {
        $this->data = (array)$data;
    }

    public function getDbEngine($default = null)
    {
       return $this->get2DAttribute('db', 'engine', $default);
    }

    public function getDbHost($default = null)
    {
       return $this->get2DAttribute('db', 'host', $default);
    }

    public function getDbDatabase($default = null)
    {
       return $this->get2DAttribute('db', 'database', $default);
    }

    public function getDbUsername($default = null)
    {
       return $this->get2DAttribute('db', 'username', $default);
    }

    public function getDbPassword($default = null)
    {
       return $this->get2DAttribute('db', 'password', $default);
    }

    private function get2DAttribute($key1, $key2, $default)
    {
        if (is_array($this->data[$key1]) && array_key_exists($key2, $this->data[$key1])) {
            return $this->data[$key1][$key2];
        } else {
            return $default;
        }
    }
}
