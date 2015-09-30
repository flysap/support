<?php

namespace Flysap\Support\Traits;

use Flysap\Support\Group;

trait ElementsTrait {

    /**
     * @var array
     */
    protected $elements = array();

    /**
     * Remove an field.
     *
     * @param $key
     * @return $this
     */
    public function removeElement($key) {
        if (isset($this->elements[$key]))
            unset($this->elements[$key]);

        return $this;
    }

    /**
     * Remove all form elements
     *
     * @return Zend_Form
     */
    public function clearElements() {
        $this->elements = array();

        return $this;
    }

    /**
     * Check if exists element .
     *
     * @param $key
     * @return bool
     */
    public function hasElement($key) {
        if( !array_key_exists($key, $this->elements) )
            return false;

        return true;
    }

    /**
     * Get an field .
     *
     * @param $key
     * @return mixed
     */
    public function getElement($key) {
        if (isset($this->elements[$key]))
            return ($this->elements[$key]);
    }

    /**
     * Get all elements ..
     *
     * @param array $keys
     * @return array
     */
    public function getElements(array $keys = array()) {
        return array_only($this->elements, count($keys) ? $keys : array_keys($this->elements));
    }

    /**
     * Add new element .
     *
     * @param $element
     * @param bool $group
     * @return $this
     */
    public function addElement($key, $element, $group = false) {
        $this->addElements([
            $key => $element
        ], $group);

        return $this;
    }

    /**
     * Add multiple elements at once
     *
     * @param  array $elements
     * @param bool $group
     * @return $this
     */
    public function addElements(array $elements, $group = false) {
        array_walk($elements, function($element, $key) use($group) {

            if( $group ) {
                if( in_array( 'addGroup', get_class_methods(get_class($this)) ) ) {
                    $this->addGroup(
                        $element->hasAttribute('group') ? $element->getAttribute('group') : Group::DEFAULT_GROUP_NAME, [$key => $element]
                    );
                }
            }

            $this->elements[$key] = $element;
        });

        return $this;
    }

    /**
     * Set form elements (overwrites existing elements)
     *
     * @param  array $elements
     * @param bool $group
     * @return Zend_Form
     */
    public function setElements(array $elements, $group = false) {
        $this->clearElements();

        return $this->addElements($elements, $group);
    }
}