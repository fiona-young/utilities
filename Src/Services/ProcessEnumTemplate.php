<?php
namespace Matters\Utilities\Services;

use Matters\Utilities\Dtos\EnumTemplate;

class ProcessEnumTemplate{

    const METHOD_NAME = 'methodName';

    public function process(EnumTemplate $enumTemplate){
        $dataArray = [];
        $transposeArray = [];
        foreach($enumTemplate->getEnumData() as $methodName => $data){
            $methodNameWithQuotes = $this->addQuotesIfRequired($methodName);
            $temp=[self::METHOD_NAME=>$methodNameWithQuotes];
            $transposeArray[self::METHOD_NAME][]=$methodNameWithQuotes;
            foreach($data as $key=>$value){
                $value = $this->addQuotesIfRequired($value);
                $label = $enumTemplate->getEnumKeys()[$key];
                $temp[$label]=$value;
                $transposeArray[$label][]=$value;
            }
            $dataArray[$methodName]=$temp;
        }
        $enumTemplate->setEnumData($dataArray);
        $enumTemplate->setEnumTranspose($transposeArray);
        $enumTemplate->setEnumKeys(array_keys($transposeArray));
        $enumTemplate->setEnumTypes(array_keys($dataArray));
        $enumTemplate->setEnumFindBy(array_merge([self::METHOD_NAME],$enumTemplate->getEnumFindBy([])));
        return $enumTemplate;
    }

    private function addQuotesIfRequired($value){
        $addQuotes = "'%s'";
        if(is_string($value) && (substr($value,0,2)!='_(')){
                $value = sprintf($addQuotes, $value);
        }
        return $value;
    }

}