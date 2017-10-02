<?php

namespace Matters\Utilities\Services;

class FlattenArrayService
{
    const DELIMINATOR = '|';

    /**
     * @param $data
     * @return array
     */
    public function getWalkedList($data)
    {
        $dataList = $this->walkData((array)$data);

        return $dataList;
    }

    private function walkData(array $data, $attributes = '', $method = '')
    {
        $result = [];
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $result = array_merge($result,
                    $this->walkData($value, $attributes.$key.self::DELIMINATOR, $method.ucfirst($key)));
            } else {
                $result[$method.ucfirst($key)] = explode(self::DELIMINATOR, $attributes.$key);
            }
        }

        return $result;
    }
}