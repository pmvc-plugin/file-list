<?php
namespace PMVC\PlugIn\file_list;

class FileTime
{
    public $times;
    public $format = 'Y/m/d H:i:s';

    public function __construct($file)
    {
        $this->times = array(
            'a' => fileatime($file),
            'c' => filectime($file),
            'm' => filemtime($file)
        );
    }

    public function toString()
    {
        $result = array();
        foreach($this->times as $k=>$v){
            $result[$k] = date($this->format, $v);
        }
        return $result;
    }
}
