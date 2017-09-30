<?php
namespace Matters\Utilities\Services;

use Matters\Utilities\Exceptions\FileServiceException;

class FileService{
    private $helpers;
    public function __construct(HelperFunctionService $helpers)
    {
        $this->helpers = $helpers;
    }

    /**
     * @param $fileLocation
     * @param bool $asArray
     * @return \stdClass | array
     * @throws FileServiceException
     */
    public function getDecodeJsonFromFile($fileLocation, $asArray = true){
        $json = $this->helpers->fileGetContents($fileLocation);
        if($json === false){
            throw new FileServiceException(sprintf("location (%s) unreadable", $fileLocation));
        }
        $decoded = json_decode($json, $asArray);
        if(is_null($decoded)){
            throw new FileServiceException(sprintf("string from (%s) is unparsible json (%s)", $fileLocation, $json));
        }
        return $decoded;
    }
}