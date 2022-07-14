<?php
namespace iboxs\basic\lib;
use iboxs\basic\traits\{Convert, Encode, File, Server, Str, Http};

class Base{
    use Str,Http,Convert,Encode,File,Server;
}