<?php
namespace PMVC\PlugIn\file_list;

\PMVC\l(__DIR__.'/class.file_list.php');

${_INIT_CONFIG}[_CLASS] = '\PMVC\PlugIn\file_list\file_list';

class file_list extends \PMVC\PlugIn
{
    private $olist;
    function init(){
        $this->olist = new \FileList(); 
    }
    
    function ls(...$p){
        return $this->olist->get(...$p);
    }

    function rmdir($dir){
        $list = $this->ls($dir);
        foreach($list as $item){
            $wholePath = $item['wholePath'];
            if (is_file($wholePath)) {
                unlink($wholePath);
            } elseif (is_dir($wholePath)) {
                rmdir($wholePath);
            }
        }
        rmdir($dir);
    }
}
?>
