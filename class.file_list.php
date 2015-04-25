<?php
/**
* Get File list	
*/
class FileList {

private $filterKey='';
private $subDirLayer='max';
private $checksum;

public function __construct($bool=false){
    $this->setChecksum($bool);
}

/**
* extract list on one path 
*/
function get($path,$pattern='*',$layer=0){
    $path=$this->EndWithSlash($path);
    $d = dir($path);
    $f = array();
    while(false !== ($filename = $d->read())){
        if($filename == '.' || $filename == '..') continue;
        $wholePath = $path.$filename;
        if( is_dir($wholePath) ){
            if( $this->subDirLayer === 'max' || $this->subDirLayer < $layer ){
                $f2=$this->get($wholePath.'/',$pattern,$layer++);
                if(is_array($f2)){
                    $f=array_merge($f,$f2);
                }
            }
        }
        if(fnmatch($pattern,$wholePath)){
            $key = str_replace($this->filterKey,'',$wholePath);
            $f[$key]=array(
                    'name'=>$filename,
                    'mtime'=>filemtime($wholePath),
                    'wholePath'=>realpath($wholePath),
                    );
            if(is_file($wholePath) && $this->checksum){
                $f[$key]['hash']=sha1_file($wholePath);
            }
        }
    }
    return $f;
}

function getDirSort(){
    $args = &func_get_args();
    $a = call_user_func_array(array($this,get),$args);
    ksort($a);
    return $a;
}

/**
* filter the array key, useful in absolute path 
*/
function setFilterKey($val){
    $this->filterKey = $val;
}

/**
* set folder layer 
*/
function setSubDirLayer($val){
    $this->subDirLayer = $val;
}

/**
* extention 
*/
function getExt($file){
    $ext = explode('.',$file);
    $ext = (count($ext)>=2) ? $ext[count($ext)-1] : '';
    return $ext;
}

/**
 * checksum 
 */
function setChecksum($bool){
    $this->checksum=$bool;
}

/**
 * Make sure a string end with a /
 * @param string $str
 * @access static
 * @return string
 */
function EndWithSlash($str)
{
    $str1 = str_replace('\\','/',$str);
    if (substr($str1,strlen($str1)-1,1) != "/")
        $str = $str.'/';
    return $str;
}

} //end class



?>
