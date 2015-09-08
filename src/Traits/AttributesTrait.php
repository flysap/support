<?php

namespace Flysap\Support\Traits;

trait AttributesTrait {

    /**
     * All of the attributes set on the container.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Set an attribute to container .
     *
     * @param $key
     * @param $value
     * @return $this
     */
    public function set($key, $value) {
        $this->attributes[$key] = $value instanceof \Closure ? $value(): $value;

        return $this;
    }

    /**
     * Get an attribute from the container.
     *
     * @param  string $key
     * @param  mixed $default
     * @return mixed
     */
    public function get($key, $default = null) {
        if (array_key_exists($key, $this->attributes)) {
            return $this->attributes[$key];
        }

        return $default instanceof \Closure ? $default() : $default;
    }

    /**
     * Check if has key .
     *
     * @param $key
     * @return mixed
     */
    public function has($key) {
        return $this->get($key);
    }

    /**
     * Set attributes .
     *
     * @param array $attributes
     * @return $this
     */
    public function setAttributes(array $attributes) {
        foreach ($attributes as $key => $value) {
            $this->attributes[$key] = $value;
        }

        return $this;
    }

    /**
     * Get the attributes from the container.
     *
     * @return array
     */
    public function getAttributes() {
        return $this->attributes;
    }

    /**
     * Flush attributes .
     *
     * @return $this
     */
    public function flush() {
        $this->attributes = [];

        return $this;
    }

    /**
     * Convert the Fluent instance to an array.
     *
     * @return array
     */
    public function toArray() {
        return $this->attributes;
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array
     */
    public function jsonSerialize() {
        return $this->toArray();
    }

    /**
     * Convert the Fluent instance to JSON.
     *
     * @param  int $options
     * @return string
     */
    public function toJson($options = 0) {
        return json_encode($this->toArray(), $options);
    }

}