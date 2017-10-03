<?php
namespace Matters\Utilities\Services;

use Matters\Utilities\Dtos\EnumTemplate;
use Matters\Utilities\Exceptions\UtilitiesException;

class VerifyEnumTemplate{
    const ERROR_START = 'Error VerifyEnumTemplate:';
    const ERROR_WRONG_TYPE = self::ERROR_START.' %s is not a %s (%s)';
    public function verify(EnumTemplate $enumTemplate){
        foreach(['Directory','Namespace','ClassName'] as $attribute){
            $this->verifyIsString($enumTemplate, $attribute);
        }
        foreach(['EnumKeys','EnumFindBy','EnumData'] as $attribute){
            $this->verifyIsArray($enumTemplate, $attribute);
        }
        if(count($enumTemplate->getEnumData())==0){
            throw new UtilitiesException(self::ERROR_START.' enumData is empty array');
        }
    }


    private function verifyIsString(EnumTemplate $enumTemplate, $method){
        $value = $enumTemplate->{'get'.$method}();
        if(!is_string($value) || is_numeric($value) || empty($value)) {
            throw new UtilitiesException(sprintf(self::ERROR_WRONG_TYPE, lcfirst($method), 'string', $value));
        }
    }

    private function verifyIsArray(EnumTemplate $enumTemplate, $method){
        $value = $enumTemplate->{'get'.$method}();
        if(!is_array($value)) {
            throw new UtilitiesException(sprintf(self::ERROR_WRONG_TYPE, lcfirst($method), 'array', $value));
        }
    }

    private function getNotArrayError($attribute, $value){
        return sprintf(self::ERROR_WRONG_TYPE, $attribute, 'array', $value);
    }
}