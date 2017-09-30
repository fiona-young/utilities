<?php
namespace Matters\Utilities\Services;

class HelperFunctionService
{
    /**
     * @param $fileName
     * @return bool|string
     */
    public function fileGetContents($fileName){
        return @file_get_contents($fileName);
    }

    public function filePutContents($fileName, $data){
        return @file_put_contents($fileName, $data);
    }
}