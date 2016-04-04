<?php
namespace PMVC\PlugIn\file_list;
${_INIT_CONFIG}[_CLASS] = __NAMESPACE__.'\read';

class read
{
    function __invoke($filename, $callback, $bufferSize = 4096)
    {
        // Open the file
        $f = fopen($filename, "rb");
        if ($f) {
            $content = '';
            $continue = true;
            while (!feof($f) && $continue) {
                $buffer = explode("\n", fgets($f, $bufferSize));
                $content.= $buffer[0];
                if (count($buffer)>1) {
                    array_shift($buffer); 
                    foreach ($buffer as $b) {
                        $continue = call_user_func($callback,$content);
                        $content = $b;
                        if (!$continue) {
                            break;
                        }
                    }
                }
            }
            fclose($f);
        }
    }
}
