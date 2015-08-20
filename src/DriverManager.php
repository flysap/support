<?php

namespace Flysap\Support;

abstract class DriverManager {

    /**
     * The array of created "drivers".
     *
     * @var array
     */
    protected $instances = [];

    /**
     * @var
     */
    protected $drivers;

    /**
     * Get the default driver name.
     *
     * @return string
     */
    abstract protected function getDefaultDriver();

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
        if (! isset($this->instances[$driver])) {
            $this->instances[$driver] = $this->createDriver($driver);
        }

        return $this->instances[$driver];
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

        $class = $drivers[$driver]['class'];

        if (! class_exists($class))
            throw new DriverException("Driver [$driver] not found.");

        return (new $class(
            array_except($drivers[$driver], 'class')
        ));
    }

    /**
     * Set drivers .
     *
     * @param array $drivers
     * @return $this
     */
    public function setDrivers(array $drivers) {
        $this->drivers = $drivers;

        return $this;
    }

    /**
     * Get drivers .
     *
     * @return mixed
     */
    public function getDrivers() {
        return $this->drivers;
    }

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