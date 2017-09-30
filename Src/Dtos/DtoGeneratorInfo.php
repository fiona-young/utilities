<?php

namespace Matters\Utilities\Dtos;

class DtoGeneratorInfo
{

    private $data;

    public function __construct($data = [])
    {
        $this->data = (array)$data;
    }

    public function getDtoData($default = null)
    {
        if (is_array($this->data) && array_key_exists("dtoData", $this->data)) {
            return $this->data["dtoData"];
        } else {
            return $default;
        }
    }

    public function getClassName($default = null)
    {
        if (is_array($this->data) && array_key_exists("className", $this->data)) {
            return $this->data["className"];
        } else {
            return $default;
        }
    }

    public function getNamespace($default = null)
    {
        if (is_array($this->data) && array_key_exists("namespace", $this->data)) {
            return $this->data["namespace"];
        } else {
            return $default;
        }
    }

    public function getDirectory($default = null)
    {
        if (is_array($this->data) && array_key_exists("directory", $this->data)) {
            return $this->data["directory"];
        } else {
            return $default;
        }
    }

    public function getSetters($default = null)
    {
        if (is_array($this->data) && array_key_exists("setters", $this->data)) {
            return $this->data["setters"];
        } else {
            return $default;
        }
    }

    public function getGetters($default = null)
    {
        if (is_array($this->data) && array_key_exists("getters", $this->data)) {
            return $this->data["getters"];
        } else {
            return $default;
        }
    }

    public function setDtoData($dtoData)
    {
        $this->data["dtoData"] = $dtoData;
    }

    public function setClassName($className)
    {
        $this->data["className"] = $className;
    }

    public function setNamespace($namespace)
    {
        $this->data["namespace"] = $namespace;
    }

    public function setDirectory($directory)
    {
        $this->data["directory"] = $directory;
    }

    public function setSetters($setters)
    {
        $this->data["setters"] = $setters;
    }

    public function setGetters($getters)
    {
        $this->data["getters"] = $getters;
    }
}
