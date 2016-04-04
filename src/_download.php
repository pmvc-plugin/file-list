<?php
namespace PMVC\PlugIn\file_list;
${_INIT_CONFIG}[_CLASS] = __NAMESPACE__.'\download';
\PMVC\l(__DIR__.'/_dump.php');

class download extends dump
{
    function processHeader($filenamem, $newName)
    {
        $info = \PMVC\plug('file_info')
            ->file($filenamem);
        $contentType = $info->getContentType();
        $size = $info->getSize()->size;
        if (is_null($newName)) {
            $newName = $info->pathinfo['basename'];
        }
        header('Content-Description: File Transfer');
        header('Content-Type: '. $contentType);
        header('Content-Disposition: attachment; filename="'.$newName.'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: '. $size);
    }
}

