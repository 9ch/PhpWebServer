<?php

use Icarus\Station\PHPServer\Server;
use Icarus\Station\PHPServer\Request;
use Icarus\Station\PHPServer\Response;

array_shift($argv);

if (empty($argv)) {
    $port = 80;
}else {
    $port = (int)array_shift($argv);
}

require_once "./vendor/autoload.php";

$server = new Server("127.0.0.1",$port);

$server->listen(function(Request $request){
    return "HTTP/1.1 " . 200 . " " . 'OK';
});
