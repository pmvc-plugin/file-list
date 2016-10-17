<?php
namespace PMVC\PlugIn\file_list;

/**
* Get File list    
*/
class FileList
{
    private $_maskKeyLen = 0;
    private $subDirLayer='max';
    private $checksum;
    private $exclude = array('.','..');
    private $callBack = null;
    public $debug = false;

    public function __construct($bool=false)
    {
        $this->setChecksum($bool);
    }

    public function fnmatch_array($string, $array)
    {
        foreach ($array as $a) {
            if (fnmatch($a, $string)) {
                return true;
            }
        }
        return false;
    }

    /**
     * extract list on one path 
     */
    public function get($path, $pattern='*', $layer=0)
    {
        if (!is_dir($path)) {
            $pathInfo = pathinfo($path);
            if (is_dir($pathInfo['dirname'])) {
                $path = $pathInfo['dirname'];
                if (!empty($pathInfo['basename'])) {
                    $pattern = $pathInfo['basename'];
                }
            } else {
                return !trigger_error('['.$path.'] is not a folder');
            }
        }
        $path=$this->EndWithSlash($path);
        $d = scandir($path);
        if (!$d) {
            $d = [];
        }
        $f = [];
        $maskKeyLen = $this->_maskKeyLen;
        foreach($d as $filename) {
            $wholePath = $path.$filename;
            $key = substr( $wholePath, $maskKeyLen, (strlen($wholePath)-$maskKeyLen) );
            if ( $this->fnmatch_array($filename,$this->exclude) ||
                 $this->fnmatch_array($key,$this->exclude)
            ) {
                continue;
            }
            $this->debug($key);
            $realPath = (is_link($wholePath)) ? $wholePath : realpath($wholePath);
            if ( is_dir($wholePath) && !is_link($wholePath)) {
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
                    'wholePath'=>$finialPath,
                );
                if (is_file($wholePath) && $this->checksum) {
                    $f[$key]['hash']=sha1_file($wholePath);
                }
                if ($this->callBack) {
                    $f[$key] = call_user_func($this->callBack,$f[$key]);
                }
            }
        }
        return $f;
    }

    private function debug($str)
    {
        if (!$this->debug) {
            return null;
        }
        var_dump($str);
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
    public function maskKey($val)
    {
        if ('/'===$val) {
            return null;
        }
        $this->_maskKeyLen = strlen($val);
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
     * callBack 
     */
    public function setCallBack($func)
    {
        if (is_callable($func)) {
            $this->callBack = $func;
        }
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

