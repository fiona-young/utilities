<?php
namespace Matters\GeneticSim\Containers;

use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class Symfony
{


    /**
     * @var Symfony
     */
    private static $instance;

    /**
     * @var bool
     */
    private $isCompiled = false;

    const CACHE_CONTAINER_NAME = 'GeneticCachedDIContainer';

    /**
     * @var Container
     */
    private $container;

    /**
     * @var ContainerBuilder
     */
    private $containerBuilder;

    private function __construct()
    {
        $this->containerBuilder = new ContainerBuilder();
    }


    /**
     * @param $serviceName
     * @return object
     */
    public function get($serviceName)
    {
        return $this->container->get($serviceName);

    }


    /**
     * @param $serviceName
     * @return boolean
     */
    public function has($serviceName)
    {
        return $this->container->has($serviceName);
    }

    /**
     * @return ContainerBuilder
     */
    private function loadConfiguration()
    {

        $loader = new YamlFileLoader($this->containerBuilder, new FileLocator($this->getDIConfigPath()));

        $loader->load("services.yml");

        return $this->containerBuilder;
    }

    public function compile()
    {
        if ($this->isCompiled) {
            return;
        }

        $file = $this->getDICacheFilePath();

        $containerConfigCache = new ConfigCache($file, $this->isDebug());

        if (!$containerConfigCache->isFresh()) {

            $containerBuilder = $this->loadConfiguration();

            $containerBuilder->compile();

            $dumper = new PhpDumper($containerBuilder);

            $containerConfigCache->write(
                $dumper->dump(array('class' => self::CACHE_CONTAINER_NAME)),
                $containerBuilder->getResources()
            );
        }

        $this->container = $this->createNewCachedContainer();

        $this->isCompiled = true;
    }

    public function recompile()
    {
        $this->isCompiled = false;
        $this->compile();
    }

    /**
     * @return bool
     */
    public function isCompiled() {
        return $this->isCompiled;
    }


    /**
     * @return Container
     */
    private function  createNewCachedContainer()
    {
        require_once($this->getDICacheFilePath());

        $className = "\\" . self::CACHE_CONTAINER_NAME;

        $container = new $className;

        return $container;
    }


    /**
     * @return string
     */
    private function getDICacheFilePath()
    {
        return $this->getBasePath() . "/Cache/container.php";
    }

    /**
     * @return string
     */
    private function getDIConfigPath()
    {
        return $this->getBasePath() . "/Config/";
    }

    /**
     * @return bool
     */
    private function isDebug()
    {
        return true;
    }

    public function setParameter($key, $value)
    {
        $this->containerBuilder->setParameter($key, $value);
    }

    /**
     * @return Container
     * @throws \Exception
     */
    public function getContainer()
    {

        if (!$this->container) {
            throw new \Exception("Compile step must be executed");
        }

        return $this->container;

    }

    /**
     * @return self
     */
    public static function getInstance()
    {
        if(self::$instance == null){
            self::$instance = new Symfony();
        }

        return self::$instance;
    }


    private static function getBasePath(){
        return  realpath(__DIR__);
    }

}