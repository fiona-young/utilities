<?php

namespace Matters\Utilities\Generators;

use Matters\Utilities\Dtos\DtoGeneratorInfo;
use Matters\Utilities\Exceptions\DtoGeneratorServiceException;
use Matters\Utilities\Services\FileService;

class DtoGeneratorService
{
    const PARAM_DTO_DATA = 'dtoData';
    const PARAM_CLASS_NAME = 'className';
    const PARAM_NAMESPACE = 'namespace';
    private $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function loadFile($fileLocation)
    {
        $data = $this->fileService->getDecodeJsonFromFile($fileLocation);
        $dtoGeneratorInfo = new DtoGeneratorInfo();
        if (array_key_exists(self::PARAM_DTO_DATA, $data)) {
            $dtoGeneratorInfo->setDtoData($data[self::PARAM_DTO_DATA]);
        } else {
            throw new DtoGeneratorServiceException('Data '.self::PARAM_DTO_DATA.' not set in input file '.$fileLocation);
        }
        foreach ([self::PARAM_CLASS_NAME, self::PARAM_NAMESPACE] as $param) {
            if (!array_key_exists($param, $data)) {
                throw new DtoGeneratorServiceException('Data '.$param.' not set in input file '.$fileLocation);
            }
            $dtoGeneratorInfo->setAttribute($param, $data[$param]);
        }

        return $dtoGeneratorInfo;
    }

    public function process($fileLocation){
        $dtoGeneratorInfo = $this->loadFile($fileLocation);
        $a = 1;
    }
}