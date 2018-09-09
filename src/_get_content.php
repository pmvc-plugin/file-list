<?php

namespace PMVC\PlugIn\file_list;

${_INIT_CONFIG}[_CLASS] = __NAMESPACE__.'\GetContent';

class GetContent
{
  public function __invoke($source)
  {
      ob_start();
      $isOK = readfile($source);
      $content = ob_get_contents();
      ob_end_clean();
      return [
        'content'=>$content,
        'ok'=> false !== $isOK
      ];
  }
}
