<?php
namespace Matters\Utilities\Services;
use Matters\Utilities\Dtos\DtoGeneratorInfo;

class ClassWriterService{
    const GETTER = <<<TEXT
    public function get%METHOD%(\$default = null)
    {
       return \$this->get%DEPTH%Attribute(%PARAMETER_LIST%, \$default);
    }
TEXT;
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

    const GETTER_GENERAL= <<<TEXT
    private function get%DEPTH%Attribute(%PARAMETER_LIST%, \$default)
    {
        if (is_array(\$this->data%PRE_INDEX%) && array_key_exists(%LAST_KEY%, \$this->data%PRE_INDEX%)) {
            return \$this->data%INDEX%;
        } else {
            return \$default;
        }
    }
TEXT;


    /**
     * @param DtoGeneratorInfo $dtoGeneratorInfo
     * @param array $flattenedList
     * @return string
     */
    public function getDtoClassText(DtoGeneratorInfo $dtoGeneratorInfo, $flattenedList)
    {
        $string = "<?php".$this->end().$this->end();
        $string .= "namespace ".$dtoGeneratorInfo->getNamespace().$this->end(';').$this->end();
        $string .= "class ".$dtoGeneratorInfo->getClassName().$this->end().$this->end('{').$this->end();
        $string .= self::HEAD.$this->end();
        if ($dtoGeneratorInfo->getGetters(true)) {
            $string .= $this->getGetters($flattenedList);
        }
        if ($dtoGeneratorInfo->getSetters(true)) {
            $string .= $this->getSetters($flattenedList);
        }
        $string .= $this->end('}');

        return $string;
    }

    /**
     * @param array $flattenedList
     * @return string
     */
    private function getGetters($flattenedList)
    {
        $string = "";
        $getterDepths = [];
        $search = ['%METHOD%', '%DEPTH%', '%PARAMETER_LIST%'];
        foreach ($flattenedList as $method => $classKeys) {
            $depth = count($classKeys);
            $getterDepths[$depth] = $depth;
            $replacements = [
                $method,
                $this->getDepthLabel($depth),
                "'".implode("', '", $classKeys)."'",
            ];
            $string .= $this->insertData($search, $replacements, self::GETTER);
        }
        $this->getGeneralGetters($getterDepths);
        return $string;
    }

    private function getGeneralGetters($getterDepths)
    {
        $search = ['%DEPTH%', '%INDEX%', '%PARAMETER_LIST%', '%PRE_INDEX%', '%LAST_KEY%'];
        $string = '';
        foreach ($getterDepths as $depth) {
            $keys = $this->getDepthKeyList($depth);
            $replacements = [
                $this->getDepthLabel($depth),
                $this->getArrayAsIndex($keys),
                implode(', ', $keys),
                $this->getArrayAsIndex(array_slice($keys, 0, -1)),
                end($keys),
            ];
            $string .= $this->insertData($search, $replacements, self::GETTER_GENERAL);

            return $string;
        }
    }

    private function getDepthLabel($depth)
    {
        return ($depth == 1) ? '' : $depth.'D';
    }

    private function getDepthKeyList($depth)
    {
        if ($depth == 1) {
            return ['$key'];
        } else {
            return array_map(function ($a) {
                return '$key'.$a;
            }, range(1, $depth));
        }
    }

    /**
     * @param array $flattenedList
     * @return string
     */
    private function getSetters($flattenedList)
    {
        $search = ['%METHOD%', '%INDEX%', '%PARAMETER%'];
        $string = "";
        foreach ($flattenedList as $method => $classKeys) {
            $replacements = [
                $method,
                $this->getArrayAsIndex($classKeys, true),
                lcfirst($method),
            ];
            $string .= $this->insertData($search, $replacements, self::SETTER);
        }

        return $string;
    }

    private function insertData($search, $replace, $format)
    {
        $string = $this->end();
        $string .= str_replace($search, $replace, $format);
        $string .= $this->end();

        return $string;
    }

    private function getArrayAsIndex($array, $addQuotes = false)
    {
        if (empty($array)) {
            return "";
        } elseif ($addQuotes) {
            return "['".implode("']['", $array)."']";
        } else {
            return '['.implode('][', $array).']';
        }
    }

    private function end($string = "")
    {
        return $string."\n";
    }

}