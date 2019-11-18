<?php

namespace Icarus\Station\PHPServer;

class Server
{

    protected $host = null;
    protected $port = null;
    protected $socket = null;

    public function __construct($host, $port)
    {
        $this->host = $host;
        $this->port = $port;
        $this->createSocket();
        $this->bind();
    }

    protected function createSocket()
    {
        $this->socket = socket_create(AF_INET, SOCK_STREAM, 0);
    }

    protected function bind()
    {
        if (!socket_bind($this->socket,$this->host, $this->port)) {
            throw  new \Exception("未能绑定socket: " . $this->host . $this->port . socket_strerror(socket_last_error()));
        }
    }

    public function listen(callable $callback)
    {
        while (true) {
            socket_listen($this->socket);
            if (!$client = socket_accept($this->socket)) {
                socket_close($client);
                continue;
            }
            $data = socket_read($client, 1024);
            $request = Request::withHeaderString($data);
            $response = call_user_func($callback, $request);
            if (!$response | !$response instanceof Response) {
                $response = Response::error(404);
            }
            $response = (string)$response;
            socket_write($client,$response,strlen($response));
            socket_close($client);
        }
    }
}
