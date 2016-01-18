<?php
namespace PMVC\PlugIn\file_list;

/**
 * Get File Size
 */
class FileSizeHelper 
{
    public $size;

    public function __construct($file)
    {
        $this->size = filesize($file);
    }

    public function convert($bytes)
    {
        $bytes = floatval($bytes);
        $units = array(
            new SizeUnits('TB', pow(1024, 4)),
            new SizeUnits('GB', pow(1024, 3)),
            new SizeUnits('MB', pow(1024, 2)),
            new SizeUnits('KB', 1024),
            new SizeUnits('B', 1)
        );
        foreach($units as $unit){
            if ($bytes >= $unit->value) {
                $result = $bytes / $unit->value;
                $result = str_replace(
                    '.', 
                    ',',
                    strval(round($result, 2))
                ).' '.$unit->string;
                break;
            }
        }
        return $result;
    }

    public function __toString()
    {
        return $this->convert($this->size);
    }

}

class SizeUnits
{
    public $string;
    public $value;
    public function __construct($s, $v)
    {
        $this->string = $s;
        $this->value = $v;
    }
}
