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
        if (is_array($this->data["db"]) && array_key_exists("engine", $this->data["db"])) {
            return $this->data["db"]["engine"];
        } else {
            return $default;
        }
    }

    public function getDbHost($default = null)
    {
        if (is_array($this->data["db"]) && array_key_exists("host", $this->data["db"])) {
            return $this->data["db"]["host"];
        } else {
            return $default;
        }
    }

    public function getDbDatabase($default = null)
    {
        if (is_array($this->data["db"]) && array_key_exists("database", $this->data["db"])) {
            return $this->data["db"]["database"];
        } else {
            return $default;
        }
    }

    public function getDbUsername($default = null)
    {
        if (is_array($this->data["db"]) && array_key_exists("username", $this->data["db"])) {
            return $this->data["db"]["username"];
        } else {
            return $default;
        }
    }

    public function getDbPassword($default = null)
    {
        if (is_array($this->data["db"]) && array_key_exists("password", $this->data["db"])) {
            return $this->data["db"]["password"];
        } else {
            return $default;
        }
    }
}
