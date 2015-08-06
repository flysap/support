<?php

namespace Flysap\Support\Traits;

trait ElementAttributes {

    /**
     * @var array
     */
    protected $attributes = array();

    /**
     * Set multiple form attributes at once
     *
     * Overwrites any previously set attributes.
     *
     * @param  array $attribs
     * @return Form
     */
    public function setAttributes(array $attribs) {
        $this->clearAttributes();

        return $this->addAttributes($attribs);
    }

    /**
     * Add attribute .
     *
     * @param $key
     * @param $value
     * @return $this
     */
    public function setAttribute($key, $value) {
        $key = (string)$key;
        $this->attributes[$key] = $value;

        return $this;
    }

    /**
     * Set data attribute
     *
     * @param $key
     * @param $value
     * @return $this
     */
    public function setDataAttribute($key, $value) {
        $this->setAttribute('data-' . $key, $value);

        return $this;
    }

    /**
     * Add multiple form attributes at once
     *
     * @param  array $attribs
     * @return $this
     */
    public function addAttributes(array $attribs) {
        array_walk($attribs, function($value, $key) {
            $this->setAttribute($key, $value);
        });

        return $this;
    }

    /**
     * Retrieve a single form attribute
     *
     * @param  string $key
     * @return mixed
     */
    public function getAttribute($key) {
        $key = (string)$key;
        if (! isset($this->attributes[$key])) {
            return null;
        }

        return $this->attributes[$key];
    }

    /**
     * Check if has attribute .
     *
     * @param $key
     * @return bool
     */
    public function hasAttribute($key) {
        $key = (string)$key;
        if (! isset($this->attributes[$key])) {
            return false;
        }

        return true;
    }

    /**
     * Retrieve all form attributes/metadata
     *
     * @return array
     */
    public function getAttributes() {
        return $this->attributes;
    }

    /**
     * Remove attribute
     *
     * @param  string $key
     * @return bool
     */
    public function removeAttribute($key) {
        if (isset($this->attributes[$key])) {
            unset($this->attributes[$key]);
            return true;
        }

        return false;
    }

    /**
     * Clear all form attributes
     *
     * @return Zend_Form
     */
    public function clearAttributes() {
        $this->attributes = array();
        return $this;
    }

    /**
     * Render attributes .
     *
     * @param array $except
     * @return string
     */
    protected function renderAttributes($only = array()) {
        $result = '';

        $attributes = array_only($this->getAttributes(), $only);

        foreach ($attributes as $attribute => $value) {
            if( $value instanceof \Closure )
                $value = $value();

            $result .= " {$attribute}=\"{$value}\"";
        }

        return $result;
    }
}