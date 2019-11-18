<?php

namespace Icarus\Station\PHPServer;

class Request
{
    protected $uri = '';
    protected $method = '';
    protected $params = [];
    protected $headers = [];

    public function __construct($method, $uri, $headers)
    {
        $this->method = strtolower($method);
        $this->headers = $headers;
        list($this->uri, $param) = explode('?', $uri);
        parse_str($param, $this->params);
    }

    public function method()
    {
        return $this->method;
    }

    public function headers($key, $default = null)
    {
        if (isset($this->headers[$key])) {
            $default = $this->headers[$key];
        }
        return $default;
    }

    public function uri()
    {
        return $this->uri;
    }

    public function params($key, $default = null)
    {
        if (isset($this->params[$key])) {
            $default = $this->params($key);
        }
        return $default;
    }


    public static function withHeaderString($data)
    {
        $headers = explode("\n", $data);
        list($method, $uri) = explode(" ", array_shift($headers));
        $header = [];
        foreach ($headers as $value) {
            $value = trim($value);
            if (strpos($value, ":") !== false) {
                list($key, $value) = explode(":", $value);
                $header[$key] = $value;
            }
        }
        return new static($method, $uri, $header);
    }

    public function __toString()
    {
        return json_encode($this->headers);
    }
}