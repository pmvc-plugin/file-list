<?php

namespace PMVC;

use PHPUnit_Framework_TestCase;

class GetContentTest extends PHPUnit_Framework_TestCase
{
  private $_plug = 'file_list';

  public function testDump()
  {
    $p = \PMVC\plug($this->_plug);
    $file = __DIR__.'/../resources/demo.txt';
    $content = $p->get_content($file);
    $this->assertEquals(
      join("\n",range(1,6))."\n",
      $content['content']
    );
    $this->assertTrue($content['ok']);
  }
}
