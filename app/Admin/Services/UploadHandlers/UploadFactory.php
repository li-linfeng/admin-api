<?php

namespace App\Admin\Services\UploadHandlers;

use InvalidArgumentException;


class UploadFactory
{

    protected $drivers = [];

    public function driver($name = null)
    {
        if (!$name) {
            return $this->getDefaultDriver();
        }
        return $this->resolve($name);
    }

    protected function resolve($name)
    {
        if ($this->drivers[$name]) {
            return $this->drivers[$name];
        }
        $driver = $this->getDriverFromConfig($name);
        if (!$driver) {
            throw new InvalidArgumentException(
                " upload driver is not defined."
            );
        }
        $this->drivers[$name] = new  $driver;
        return $this->drivers[$name];
    }


    public function getDefaultDriver()
    {
        $default = config('upload.default');
        $class =  config("upload.drivers.{$default}");
        $this->drivers['default'] = new  $class;
        return $this->drivers['default'];
    }


    protected function getDriverFromConfig($name)
    {
        return  config("upload.drivers.{$name}");
    }
}
