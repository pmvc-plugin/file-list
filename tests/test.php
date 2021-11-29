<?php
namespace PMVC;

use PMVC\TestCase;

class FileListTest extends TestCase
{
    private $_plug = 'file_list';
    function testFileList()
    {
        $files = \PMVC\plug($this->_plug)->ls('./');
        $this->assertTrue(!empty($files['./file_list.php']));
    }

    function testTail()
    {
        $p = \PMVC\plug($this->_plug);
        $file = __DIR__.'/resources/demo.txt';
        $file = realpath($file);
        $arr = [1, 2, 3, 4, 5, 6];
        $last = '';
        $p->tail($file, function($c) use (&$arr, &$last){
            $this->assertEquals((string)array_pop($arr), $c);
            $last = $c;
            return true;
        });
        $this->assertEquals('1', $last);
    }

    function testRead()
    {
        $p = \PMVC\plug($this->_plug);
        $file = __DIR__.'/resources/demo.txt';
        $file = realpath($file);
        $arr = [1, 2, 3, 4, 5, 6];
        $last = '';
        $p->read($file, function($c) use (&$arr, &$last){
            $this->assertEquals((string)array_shift($arr), $c);
            $last = $c;
            return true;
        });
        $this->assertEquals('6', $last);
    }

}
