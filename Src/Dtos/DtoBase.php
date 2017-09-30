<?php
namespace Matters\Utilities\Dtos;
use Matters\Utilities\Exceptions\DtoException;

abstract class DtoBase{
    protected $data;
    public function __construct($data = []){
        $this->data = (array)$data;
    }

    public function __call($name, $arguments){
        $firstThreeLetters = substr($name,0,3);
        $attribute = lcfirst(substr($name,3));
        if($firstThreeLetters === 'get'){
            return $this->getAttribute($attribute);
        }elseif($firstThreeLetters === 'set'){
            $this->setAttribute($attribute, $arguments[0]);
            return null;
        }else{
            throw new DtoException("method $name not recognised");
        }
    }

    public function setAttribute($attribute, $value){
        $this->data[$attribute] = $value;
    }

    public function getAttribute($attribute)
    {
        if (array_key_exists($attribute, $this->data)) {
            return $this->data[$attribute];
        } else {
            return null;
        }
    }
}
