<?php
namespace PMVC\PlugIns;

\PMVC\l(__DIR__.'/class.file_list.php');

${_INIT_CONFIG}[_CLASS] = 'PMVC\PlugIns\PMVC_PLUGIN_FileList';

class PMVC_PLUGIN_FileList extends \PMVC\PLUGIN
{
    private $olist;
    function init(){
        $this->olist = new \FileList(); 
    }
    
    function ls(...$p){
        return $this->olist->get(...$p);
    }
}
?>
