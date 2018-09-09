<?php
namespace PMVC\PlugIn\file_list;
${_INIT_CONFIG}[_CLASS] = __NAMESPACE__.'\dump';

class dump
{
    function __invoke($filename, $setHeader = true)
    {
        if ($setHeader) {
          $this->_processHeader($filename);
        }
        $this->_cleanBuffer();
        readfile($filename);
    }

    private function _cleanBuffer()
    {
        $has_buffer = ob_get_contents();
        if (!empty($has_buffer)) {
            ob_clean();
        }
        flush();
    }

    private function _processHeader($filename)
    {
        $contentType = \PMVC\plug('file_info')
            ->path($filename) 
            ->getContentType();
        header('Content-Type: '.$contentType);
    }
}
