<?php

include('include.php');
$p = \PMVC\plug('file_list');
$file = '/home/sys/logs/httpd/access_log';
$i = 0;
$p->tail($file, function($c) use (&$i){
    echo memory_get_usage(false)."\n";
    echo $c ."\n";
    echo $i ."\n";
    $i++;
    usleep(100);
    return true;
});
