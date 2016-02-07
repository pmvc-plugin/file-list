<?php
namespace PMVC\PlugIn\file_list;

/**
 * tail file 
 */

class FileReader
{
    static function tail($filename, $callback, $bufferSize = 4096)
    {
        // Open the file
        $f = fopen($filename, "rb");

        // Jump to last character
        fseek($f, -1, SEEK_END);

        // Read it and adjust line number if necessary
        // (Otherwise the result would be wrong if file doesn't end with a blank line)
        if(fread($f, 1) === "\n") { 
            fseek($f, -1, SEEK_CUR);
        }

        // Start reading
        $output = '';
        $chunk = '';

        // While we would like more
        $i = 0;
        $continue = true;
        while(ftell($f) > 0 && $continue)
        {
            // Figure out how far back we should jump
            $seek = min(ftell($f), $bufferSize);

            // Do the jump (backwards, relative to where we are)
            fseek($f, -$seek, SEEK_CUR);

            // Read a chunk and prepend it to our output
            $chunk = fread($f, $seek);
            $buffer = explode("\n", $chunk);
            $buffer = array_reverse($buffer);
            $output= $buffer[0].$output;
            if (count($buffer)>1) {
                array_shift($buffer); 
                foreach ($buffer as $b) {
                    $continue = call_user_func($callback,$output);
                    $output = $b;
                    if (!$continue) {
                        break;
                    }
                }
            }
            // Jump back to where we started reading
            fseek($f, -mb_strlen($chunk, '8bit'), SEEK_CUR);
        }
        // Close file and return
        fclose($f); 
    }

    static function read($filename, $callback, $bufferSize = 4096)
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
