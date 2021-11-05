<?php

namespace Nonetallt\Laravel\Route\Publish;

class RouteData
{
    private $name;
    private $method;
    private $uri;
    private $extra;

    public function __construct(string $name, string $method, Uri $uri, array $extra = [])
    {
        $this->name = $name;
        $this->method = $method;
        $this->uri = $uri;
        $this->extra = $extra;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getMethod() : string
    {
        return $this->method;
    }

    public function getUri() : Uri
    {
        return $this->uri;
    }

    public function getExtra() : array
    {
        return $this->extra;
    }

    public function toArray(bool $includeExtra = true) : array
    {
        $data =  [
            'name' => $this->name,
            'method' => $this->method,
            'uri' => $this->uri->toArray()
        ];

        if($includeExtra) {
            $data['extra'] = $this->extra;
        }

        return $data;
    }
}
