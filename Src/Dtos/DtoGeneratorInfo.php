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
       return $this->getAttribute('dtoData', $default);
    }

    public function getClassName($default = null)
    {
       return $this->getAttribute('className', $default);
    }

    public function getNamespace($default = null)
    {
       return $this->getAttribute('namespace', $default);
    }

    public function getDirectory($default = null)
    {
       return $this->getAttribute('directory', $default);
    }

    public function getSetters($default = null)
    {
       return $this->getAttribute('setters', $default);
    }

    public function getGetters($default = null)
    {
       return $this->getAttribute('getters', $default);
    }

    private function getAttribute($key, $default)
    {
        if (is_array($this->data) && array_key_exists($key, $this->data)) {
            return $this->data[$key];
        } else {
            return $default;
        }
    }

    public function setDtoData($dtoData)
    {
        $this->data['dtoData'] = $dtoData;
    }

    public function setClassName($className)
    {
        $this->data['className'] = $className;
    }

    public function setNamespace($namespace)
    {
        $this->data['namespace'] = $namespace;
    }

    public function setDirectory($directory)
    {
        $this->data['directory'] = $directory;
    }

    public function setSetters($setters)
    {
        $this->data['setters'] = $setters;
    }

    public function setGetters($getters)
    {
        $this->data['getters'] = $getters;
    }
}
