<?php
require_once '../vendor/autoload.php';
use iboxs\basic\Basic;

$ret=Basic::Http('https://search.hwt88.cn/keys')->setHeader([
    'Content-Type' => 'application/json'
])->postJson([
    'key'=>'aaa'
]);
var_dump($ret);