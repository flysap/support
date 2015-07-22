<?php

namespace Flysap\FormBuilder\Traits;

use Flysap\FormBuilder\Form;

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
     * Add multiple elements at once
     *
     * @param  array $elements
     * @param bool $group
     * @return $this
     */
    public function addElements(array $elements, $group = false) {
        array_walk($elements, function($element, $key) use($group) {

            if( $group )
                $this->addGroup(
                    $element->hasAttribute('group') ? $element->getAttribute('group') : Form::DEFAULT_GROUP_NAME, [$key => $element]
                );

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