<?php
namespace PMVC\PlugIn\file_list;
${_INIT_CONFIG}[_CLASS] = __NAMESPACE__.'\dump';

class dump
{
    function __invoke($filename, $newName = null)
    {
        $this->processHeader($filename, $newName);
        $has_buffer = ob_get_contents();
        if (!empty($has_buffer)) {
            ob_clean();
        }
        flush();
        readfile($asset);
    }

    function processHeader($filename, $newName)
    {
        $contentType = \PMVC\plug('file_info')
            ->path($filename) 
            ->getContentType();
        header('Content-Type: '.$contentType);
    }
}
