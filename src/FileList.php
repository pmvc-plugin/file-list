<?php
namespace PMVC\PlugIn\file_list;

/**
* Get File list    
*/
class FileList
{
    private $filterKey='';
    private $subDirLayer='max';
    private $checksum;
    private $exclude = array('.','..');

    public function __construct($bool=false)
    {
        $this->setChecksum($bool);
    }

    /**
     * extract list on one path 
     */
    public function get($path, $pattern='*', $layer=0)
    {
        $path=$this->EndWithSlash($path);
        $d = scandir($path);
        $f = array();
        foreach($d as $filename) {
            $wholePath = $path.$filename;
            $key = str_replace($this->filterKey, '', $wholePath);
            if ( in_array($filename,$this->exclude) ||
                 in_array($key,$this->exclude)
            ) {
                continue;
            }
            $realPath = (is_link($wholePath)) ? $wholePath : realpath($wholePath);
            if (is_dir($wholePath)) {
                if ($this->subDirLayer === 'max' || $this->subDirLayer < $layer) {
                    $f2=$this->get($wholePath.'/', $pattern, $layer++);
                    if (is_array($f2)) {
                        $f=array_merge($f, $f2);
                    }
                }
            }
            if (fnmatch($pattern, $wholePath)) {
                $finialPath = $realPath ?: $wholePath;
                $f[$key]=array(
                    'name'=>$filename,
                    'mtime'=>filemtime($realPath),
                    'wholePath'=>$finialPath,
                );
                if (is_file($wholePath) && $this->checksum) {
                    $f[$key]['hash']=sha1_file($wholePath);
                }
            }
        }
        return $f;
    }

    public function getSortDir()
    {
        $args = &func_get_args();
        $a = call_user_func_array(array($this,'get'), $args);
        ksort($a);
        return $a;
    }

    /**
     * add exclude 
     */
    public function addExclude($val)
    {
        if (!in_array($val,$this->exclude)) {
            $this->exclude[] = $val;
        }
    }

    /**
     * filter the array key, useful in absolute path 
     */
    public function filterKey($val)
    {
        $this->filterKey = $val;
    }

    /**
     * set folder layer 
     */
    public function setSubDirLayer($val)
    {
        $this->subDirLayer = $val;
    }

    /**
     * extention 
     */
    public function getExt($file)
    {
        $ext = explode('.', $file);
        $ext = (count($ext)>=2) ? $ext[count($ext)-1] : '';
        return $ext;
    }

    /**
     * checksum 
     */
    public function setChecksum($bool)
    {
        $this->checksum=$bool;
    }

    /**
     * Make sure a string end with a /
     * @param string $str
     * @access static
     * @return string
     */
    public function EndWithSlash($str)
    {
        $str1 = str_replace('\\', '/', $str);
        if (substr($str1, strlen($str1)-1, 1) != "/") {
            $str = $str.'/';
        }
        return $str;
    }
} //end class

