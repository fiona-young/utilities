<?php
namespace Matters\Utilities\Services;
use Matters\Utilities\Dtos\EnumTemplate;
use Matters\Utilities\Exceptions\UtilitiesException;

class EnumClassWriterService{
    const INDENT = '    ';

    /**
     * @param EnumTemplate $enumTemplate
     * @return string
     */
    public function getClassText(EnumTemplate $enumTemplate)
    {
        $string = "<?php".$this->end().$this->end();
        $string .= "namespace ".$enumTemplate->getNamespace().$this->end(';').$this->end();
        $string .= "class ".$enumTemplate->getClassName().$this->end().$this->end('{').$this->end();
        $string.= $this->addVariables($enumTemplate);
        $string.= $this->addConstructor($enumTemplate);
        $string.= $this->addGetters($enumTemplate);
        $string.= $this->addFindBy($enumTemplate);
        $string.= $this->addAll($enumTemplate);
        $string.= $this->addGetStatics($enumTemplate);
        $string.= $this->addInitialize($enumTemplate);
        $string.= $this->addMaps($enumTemplate);
        $string .= $this->end('}');
        $string .= $this->end();
        $string .= $enumTemplate->getClassName().'::initialize();'.$this->end();
        return $string;
    }

    private function addAll(EnumTemplate $enumTemplate){
        $format = <<<TEXT
    /** @return %s[] */
    public static function ALL()
    {
        return self::\$ALL;
    }
TEXT;
        return sprintf($format, $enumTemplate->getClassName()).$this->end().$this->end();
    }

    private function addGetStatics(EnumTemplate $enumTemplate){
        $format = <<<TEXT
    /** @return %1\$s */
    public static function %2\$s()
    {
        return self::\$%2\$s;
    }
TEXT;
        $string = '';
        foreach($enumTemplate->getEnumTypes() as $enumType){
            $string.=sprintf($format, $enumTemplate->getClassName(), $enumType).$this->end().$this->end();
        }
        return $string;
    }

    private function addVariables(EnumTemplate $enumTemplate){
        $string = <<<TEXT
    private static \$Initialized = false;
    private static \$MAP = array();
    private static \$ALL = array();
TEXT;
        $string.=$this->end().$this->end();
        foreach($enumTemplate->getEnumTypes() as $value){
            $string.=$this->indent(1)."private static \$".$value.$this->end(';');
        }
        $string.=$this->end();
        foreach($enumTemplate->getEnumKeys() as $value){
            $string.=$this->indent(1)."private \$".$value.$this->end(';');
        }
        $string.=$this->end();
        return $string;
    }

    private function addConstructor(EnumTemplate $enumTemplate){
        $assignFormat = '$this->%1$s = $%1$s';
        $keyList = '$'.implode(', $',$enumTemplate->getEnumKeys());
        $string = $this->indent().'private function __construct('.$keyList.')'.$this->end();
        $string .= $this->indent().$this->end('{');
        foreach($enumTemplate->getEnumKeys() as $key){
            $string .= $this->indent(2).sprintf($assignFormat,$key).$this->end(';');
        }
        $string .= $this->indent().$this->end('}').$this->end();
        return $string;
    }

    private function addGetters(EnumTemplate $enumTemplate){
        $format = <<<TEXT
    public function get%s()
    {
        return \$this->%s;
    }
TEXT;
        $string='';
        foreach($enumTemplate->getEnumKeys() as $value){
            $string.=sprintf($format, ucfirst($value),$value);
            $string.=$this->end().$this->end();
        }
        return $string;
    }

    private function addFindBy(EnumTemplate $enumTemplate){
        $format = <<<TEXT
    /**
     * @param \$%2\$s
     * @return bool | %3\$s
     */
    public static function findBy%1\$s(\$%2\$s)
    {
        if (isset(self::\$MAP['%2\$s'][\$%2\$s])) {
            return self::\$MAP['%2\$s'][\$%2\$s];
        }

        return false;
    }
TEXT;
        $string='';
        foreach($enumTemplate->getEnumFindBy() as $value){
            $data = $enumTemplate->getEnumTranspose()[$value];
            if(count($data)!=count(array_unique($data))){
                throw new UtilitiesException(sprintf('Enum key "%s" selected for findBy - but not unique',$value));
            }
            $string.=sprintf($format, ucfirst($value),$value,$enumTemplate->getClassName());
            $string.=$this->end().$this->end();
        }
        return $string;
    }
    private function addInitialize(EnumTemplate $enumTemplate){
        $start = <<<TEXT
    public static function initialize()
    {
        if (self::\$Initialized) {
            return;
        }
TEXT;
        $end = <<<TEXT
        self::initializeMaps();

        self::\$Initialized = true;
    }
TEXT;
        $createStatic = 'self::$%s = new %s(%s);';
        $string=$start.$this->end().$this->end();
        foreach($enumTemplate->getEnumData() as $key=> $data){
            $string.=$this->indent(2).sprintf($createStatic, $key, $enumTemplate->getClassName(), implode(', ',$data)).$this->end();
        }
        $string.=$this->end();
        $string.=$this->indent(2).'self::$ALL = ['.$this->end();
        $before = $this->indent(3).'self::$';
        $string.=$before.implode($this->end(',').$before,$enumTemplate->getEnumTypes());
        $string.=$this->end();
        $string.=$this->indent(2).']'.$this->end(';');
        $string.=$this->end();

        $string.=$end.$this->end().$this->end();
        return $string;
    }

    private function addMaps(EnumTemplate $enumTemplate){

        $string=$this->indent().'private static function initializeMaps()'.$this->end();
        $string.=$this->indent().$this->end('{');
        foreach($enumTemplate->getEnumFindBy() as $value){
            $string.=$this->indent(2).sprintf('self::$MAP[\'%s\'] = [',$value).$this->end();
            $string.=$this->addInnerMap($this->indent(3),array_combine($enumTemplate->getEnumTypes(),$enumTemplate->getEnumTranspose()[$value]));
            $string.=$this->indent(2).'];'.$this->end().$this->end();
        }
        $string.=$this->indent().$this->end('}');
        return $string;
    }

    private function addInnerMap($whiteSpace, $mapArray){
        $result = [];
        foreach($mapArray as $key  =>$value){
            $result[]=$whiteSpace.$value.' => self::$'.$key;
        }
        return implode($this->end(','),$result).$this->end();
    }

    private function end($string = "")
    {
        return $string."\n";
    }

    private function indent($repeat = 1){
        return str_repeat(self::INDENT, $repeat);
    }

}