<?php
namespace PMVC\PlugIn\file_list;
${_INIT_CONFIG}[_CLASS] = __NAMESPACE__.'\dump';

class dump
{
    function __invoke($filename, $setHeader = true, $dumpOnEmptyHeader = true, $callback = null)
    {
        $isOK = false;
        $header = [];
        if ($setHeader) {
            $header = $this->_processHeader($filename, $setHeader);
        }
        $this->_cleanBuffer();
        if ($dumpOnEmptyHeader || !empty($header)) {
            if (is_callable($callback)) {
                $callback();
            }
            $isOK = readfile($filename);
        }
        return false !== $isOK;
    }

    private function _cleanBuffer()
    {
        $has_buffer = ob_get_contents();
        if (!empty($has_buffer)) {
            ob_clean();
        }
    }

    private function _processHeader($filename, $ext)
    {
        if (is_bool($ext)) {
            $contentType = \PMVC\plug('file_info')->
              path($filename)
                ->getContentType();
        } else {
            $contentType = \PMVC\plug('file_info')->
              getContentType($ext);
        }
        if (empty($contentType)) {
            \PMVC\dev(
                function () use ($filename) {
                    return 'Content type not found. ['.$filename.']';
                }, 'dump'
            );
            return false;
        }
        $header = ['Content-Type: '.$contentType];
        \PMVC\dev(
            function () use (&$header) {
                $old = $header;
                $header = [];
                return ['Origin Header'=>$old];
            }, 'dump'
        );
        if (defined('_ROUTER')) {
            \PMVC\callPlugin(
                \PMVC\getOption(_ROUTER),
                'processHeader',
                [$header]
            );
        } else {
            if (!empty($header[0])) {
                header($header[0]);
            }
        }
        return $header;
    }
}
