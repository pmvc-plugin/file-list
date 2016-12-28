<?php
namespace PMVC\PlugIn\file_list;
${_INIT_CONFIG}[_CLASS] = __NAMESPACE__.'\tail';

class tail
{
    function __invoke($filename, $callback, $bufferSize = 4096)
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
            if (!empty($buffer)) {
                $buffer = array_reverse($buffer);
                $output= $buffer[0].$output;
                array_shift($buffer); 
                foreach ($buffer as $b) {
                    $continue = call_user_func($callback,$output);
                    $output = $b;
                    if (!$continue) {
                        break;
                    }
                }
                if ($continue) {
                    $continue = call_user_func($callback,$output);
                }
            }
            // Jump back to where we started reading
            fseek($f, -mb_strlen($chunk, '8bit'), SEEK_CUR);
            unset($chunk, $buffer, $output, $b, $seek);
            $output = '';
        }
        // Close file and return
        fclose($f); 
    }
}
