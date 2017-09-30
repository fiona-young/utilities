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
       return $this->getLevel1Property('dtoData', $default);
    }

    public function getClassName($default = null)
    {
       return $this->getLevel1Property('className', $default);
    }

    public function getNamespace($default = null)
    {
       return $this->getLevel1Property('namespace', $default);
    }

    public function getDirectory($default = null)
    {
       return $this->getLevel1Property('directory', $default);
    }

    public function getSetters($default = null)
    {
       return $this->getLevel1Property('setters', $default);
    }

    public function getGetters($default = null)
    {
       return $this->getLevel1Property('getters', $default);
    }

    private function getLevel1Property($key1, $default)
    {
        if (is_array($this->data) && array_key_exists($key1, $this->data)) {
            return $this->data[$key1];
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
