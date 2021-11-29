<?php

namespace PMVC;

use PMVC\TestCase;

class GetContentTest extends TestCase
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
