<?php

namespace UnifiAPI;

use UnifiAPI\Exceptions\UndefinedPropertyException;

abstract class UnifiElement
{
    protected $config;
    protected $api;
    protected $site_id;
    protected $icons;

    public function __construct($data, &$api)
    {
        $this->api = $api;
        $this->config = $data;
        $this->site_id = $api->controller()->site_id();
        $this->icons = $api->icons();
    }

    public function __get($key)
    {
        if (property_exists($this->config, $key)) {
            return $this->config->$key;
        }
        $trace = debug_backtrace();
        throw new UndefinedPropertyException(
            'Undefined property via __get(): ' . $key .
                ' in ' . $trace[0]['file'] .
                ' on line ' . $trace[0]['line']
        );
    }

    public function getData()
    {
        return $this->config;
    }

    public function __isset($key)
    {
        return isset($this->config->$key);
    }

    // proxy requests for the controller through our api object
    public function controller()
    {
        return $this->api->controller();
    }
}
