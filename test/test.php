<?php
require_once '../vendor/autoload.php';
use iboxs\basic\Basic;

$systemInfo=Basic::get_system_info();
echo "你当前服务器操作系统为：".$systemInfo['os'].PHP_EOL;
echo "你当前PHP版本为：".$systemInfo['php'].PHP_EOL;
echo "你当前可上传最大文件为：".$systemInfo['upload_max_filesize'].PHP_EOL;
echo "你当前服务器最长运行时间为：".$systemInfo['max_execution_time'].PHP_EOL;
