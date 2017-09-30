<?php

use Matters\GeneticSim\Containers\Symfony;

include_once('../../../vendor/autoload.php');
$container =Symfony::getInstance();
$container->compile();
/** @var DtoGeneratorService $dtoGeneratorService */
$dtoGeneratorService = $container->get(DtoGeneratorService::class);
