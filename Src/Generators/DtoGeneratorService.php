<?php

namespace Matters\Utilities\Generators;

use Matters\Utilities\Dtos\DtoTemplate;
use Matters\Utilities\Exceptions\DtoGeneratorServiceException;
use Matters\Utilities\Exceptions\UtilitiesException;
use Matters\Utilities\Services\DtoClassWriterService;
use Matters\Utilities\Services\FileService;
use Matters\Utilities\Services\FlattenArrayService;

class DtoGeneratorService
{
    const PARAM_DTO_DATA = 'dtoData';
    const PARAM_DTO_FILE = 'dtoFile';
    private $fileService;
    private $flattenArrayService;
    private $classWriter;

    public function __construct(FileService $fileService, FlattenArrayService $flattenArrayService, DtoClassWriterService $classWriter)
    {
        $this->fileService = $fileService;
        $this->flattenArrayService = $flattenArrayService;
        $this->classWriter = $classWriter;
    }

    public function loadFile($fileLocation)
    {

        $fileLocation = $this->fileService->fromRunningDirectory($fileLocation);
        $data = $this->fileService->getDecodeJsonFromFile($fileLocation);
        $dir = $this->fileService->getDirectory($fileLocation);
        if (!array_key_exists(self::PARAM_DTO_DATA, $data)) {
            if(!array_key_exists(self::PARAM_DTO_FILE, $data)) {
                throw new DtoGeneratorServiceException('Data '.self::PARAM_DTO_DATA.'or '.self::PARAM_DTO_FILE.' not set in input file '.$fileLocation);
            }
            $data[self::PARAM_DTO_DATA]= $this->fileService->getDecodeJsonFromFile($dir. $data[self::PARAM_DTO_FILE]);
            unset($data[self::PARAM_DTO_FILE]);
        }
        $dtoTemplate = new DtoTemplate($data);
        $dtoTemplate->setDirectory($dir.$dtoTemplate->getDirectory());
        return $dtoTemplate;
    }

    public function process($fileLocation){
        if(is_null($fileLocation)){
            throw new UtilitiesException("no file selected");
        }
        $dtoTemplate = $this->loadFile($fileLocation);
        $flattenedList = $this->flattenArrayService->getWalkedList($dtoTemplate->getDtoData());
        $classText = $this->classWriter->getClassText($dtoTemplate, $flattenedList);
        $fileName = $dtoTemplate->getDirectory()."/".$dtoTemplate->getClassName().".php";
        $this->fileService->writeFile($fileName, $classText);
        return $fileName;
    }

}