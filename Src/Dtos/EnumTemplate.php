<?php

namespace Matters\Utilities\Dtos;

class EnumTemplate
{

    private $data;

    public function __construct($data = [])
    {
        $this->data = (array)$data;
    }

    public function getEnumFindBy($default = null)
    {
       return $this->getAttribute('enumFindBy', $default);
    }

    public function getEnumKeys($default = null)
    {
       return $this->getAttribute('enumKeys', $default);
    }

    public function getEnumTypes($default = null)
    {
       return $this->getAttribute('enumTypes', $default);
    }

    public function getEnumData($default = null)
    {
       return $this->getAttribute('enumData', $default);
    }

    public function getEnumTranspose($default = null)
    {
       return $this->getAttribute('enumTranspose', $default);
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

    private function getAttribute($key, $default)
    {
        if (is_array($this->data) && array_key_exists($key, $this->data)) {
            return $this->data[$key];
        } else {
            return $default;
        }
    }

    public function setEnumFindBy($enumFindBy)
    {
        $this->data['enumFindBy'] = $enumFindBy;
    }

    public function setEnumKeys($enumKeys)
    {
        $this->data['enumKeys'] = $enumKeys;
    }

    public function setEnumTypes($enumTypes)
    {
        $this->data['enumTypes'] = $enumTypes;
    }

    public function setEnumData($enumData)
    {
        $this->data['enumData'] = $enumData;
    }

    public function setEnumTranspose($enumTranspose)
    {
        $this->data['enumTranspose'] = $enumTranspose;
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
}
