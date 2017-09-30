<?php
namespace Matters\Utilities\Services;

class FlattenArrayService{
    const DELIMINATOR = '|';

    /**
     * @param $data
     * @return array
     */
    public function getFlattenedArrayList($data){
        $dataList = $this->spiderData((array)$data);
        return $dataList;
    }

    private function spiderData(array $data, $attributes = '',$method = ''){
        $result = [];
        foreach($data as $key=>$value){
            if(is_array($value)){
                $result = array_merge($result,$this->spiderData($value,$attributes.$key.self::DELIMINATOR, $method.ucfirst($key)));
            }else{
                $result[$method.ucfirst($key)] = explode(self::DELIMINATOR,$attributes.$key);
            }
        }
        return $result;
    }
}