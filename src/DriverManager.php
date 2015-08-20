<?php

namespace Flysap\Support;

abstract class DriverManager {

    /**
     * The array of created "drivers".
     *
     * @var array
     */
    protected $drivers = [];

    /**
     * Get the default driver name.
     *
     * @return string
     */
    abstract public function getDefaultDriver();

    /**
     * Get a driver instance.
     *
     * @param  string $driver
     * @return mixed
     */
    public function driver($driver = null) {
        $driver = $driver ?: $this->getDefaultDriver();

        // If the given driver has not been created before, we will create the instances
        // here and cache it so we can return it next time very quickly. If there is
        // already a driver created by this name, we'll just return that instance.
        if (! isset($this->drivers[$driver])) {
            $this->drivers[$driver] = $this->createDriver($driver);
        }

        return $this->drivers[$driver];
    }

    /**
     * Create a new driver instance.
     *
     * @param  string $driver
     * @return mixed
     *
     * @throws DriverException
     */
    protected function createDriver($driver) {
        $drivers = $this->getDrivers();

        if (! isset($drivers[$driver]))
            throw new DriverException("Driver [$driver] not supported.");

        $class = $drivers[$driver];

        if (! class_exists($class))
            throw new DriverException("Driver [$driver] not found.");

        return (new $class);
    }

    /**
     * Get all of the created "drivers".
     *
     * @return array
     */
    public function getDrivers() {
        return $this->drivers;
    }

    abstract function setDrivers(array $drivers);

    /**
     * Dynamically call the default driver instance.
     *
     * @param  string $method
     * @param  array $parameters
     * @return mixed
     */
    public function __call($method, $parameters) {
        return call_user_func_array([$this->driver(), $method], $parameters);
    }
}