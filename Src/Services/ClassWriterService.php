<?php
namespace Matters\Utilities\Services;
use Matters\Utilities\Dtos\DtoGeneratorInfo;

class ClassWriterService{
    const GETTER_PARAMS = ['%METHOD%','%INDEX%','%PARAMETER%','%PRE_INDEX%','%LAST_KEY%'];
    const GETTER = <<<TEXT
    public function get%METHOD%(\$default = null)
    {
        if (is_array(\$this->data%PRE_INDEX%) && array_key_exists("%LAST_KEY%", \$this->data%PRE_INDEX%)) {
            return \$this->data%INDEX%;
        } else {
            return \$default;
        }
    }
TEXT;
    const SETTER_PARAMS = ['%METHOD%','%INDEX%','%PARAMETER%'];
    const SETTER = <<<TEXT
    public function set%METHOD%(\$%PARAMETER%)
    {
        \$this->data%INDEX% = \$%PARAMETER%;
    }
TEXT;
    const HEAD = <<<TEXT
    private \$data;

    public function __construct(\$data = [])
    {
        \$this->data = (array)\$data;
    }
TEXT;


    /**
     * @param DtoGeneratorInfo $dtoGeneratorInfo
     * @param array $flattenedList
     * @return string
     */
    public function getDtoClassText(DtoGeneratorInfo $dtoGeneratorInfo, $flattenedList){
        $string = "<?php".$this->end().$this->end();
        $string.= "namespace ".$dtoGeneratorInfo->getNamespace().$this->end(';').$this->end();
        $string.= "class ".$dtoGeneratorInfo->getClassName().$this->end().$this->end('{').$this->end();
        $string.=self::HEAD.$this->end();
        if($dtoGeneratorInfo->getGetters(true)) {
            $string .= $this->getGetters($flattenedList);
        }
        if($dtoGeneratorInfo->getSetters(true)) {
            $string.=$this->getSetters($flattenedList);
        }
        $string.=$this->end('}');
        return $string;

    }


    /**
     * @param array $flattenedList
     * @return string
     */
    private function getGetters($flattenedList)
    {
        $string = "";
        foreach ($flattenedList as $method => $classKeys) {
            $replacements = [
                $method,
                $this->getArrayAsIndex($classKeys),
                lcfirst($method),
                $this->getArrayAsIndex(array_slice($classKeys, 0, -1)),
                end($classKeys),
            ];
            $string.=$this->insertData(self::GETTER_PARAMS, $replacements, self::GETTER);
        }
        return $string;
    }

    /**
     * @param array $flattenedList
     * @return string
     */
    private function getSetters($flattenedList)
    {
        $string = "";
        foreach ($flattenedList as $method => $classKeys) {
            $replacements = [
                $method,
                $this->getArrayAsIndex($classKeys),
                lcfirst($method),
            ];
            $string.=$this->insertData(self::SETTER_PARAMS,$replacements, self::SETTER);
        }
        return $string;
    }

    private function insertData($search, $replace, $format){
        $string = $this->end();
        $string .= str_replace($search, $replace, $format);
        $string.=$this->end();
        return $string;
    }


    private function getArrayAsIndex($array){
        if(empty($array)){
            return "";
        }else {
            return '["'.implode('"]["', $array).'"]';
        }
    }

    private function end($string=""){
        return $string."\n";
    }

}