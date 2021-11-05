<?php
namespace Nonetallt\Laravel\Route\Publish;

use Nonetallt\Laravel\Route\Publish\Exception\UriSyntaxException;

class Uri
{
    const COMPONENTS = [
        'scheme',
        'userinfo',
        'host',
        'port',
        'path',
        'query',
        'fragment'
    ];

    private $components;

    public function __construct(array $components)
    {
        $this->components = [];

        if(isset($components['user']) && isset($components['pass'])) {
            $components['userinfo'] = $components['user'] . ':' . $components['pass'];
            unset($components['user']);
            unset($components['pass']);
        }

        foreach($components as $name => $value) {

            if(! in_array($name, static::COMPONENTS)) {
                $msg = "Key '$name' of a given array is a not a valid URI component.";
                throw new \InvalidArgumentException($msg);
            }

            $this->components[$name] = $value;
        }
    }

    public function __toString() : string
    {
        throw new \Exception('TODO');
    }

    public static function fromUriString(string $uri) : self
    {
        $parsed = parse_url($uri);

        if($parsed === false) {
            $msg = "Could not parse URI: '$uri'";
            throw new UriSyntaxException($msg);
        }

        return new self($parsed);
    }

    public function getComponent(string $component) : ?string
    {
        return $this->components[$component] ?? null;
    }

    public function toArray() : array
    {
        return $this->components;
    }
}
