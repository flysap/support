<?php

namespace Flysap\Support;

use Flysap\Support\Traits\ElementAttributes;
use Flysap\Support\Traits\ElementsTrait;

class Group implements \Iterator {

    use ElementsTrait, ElementAttributes;

    const DEFAULT_GROUP_NAME = 'default';

    /**
     * @var array
     */
    private $options;

    public function __construct($elements = array(), array $options = array()) {
        if(! is_array($elements))
            $elements = (array)$elements;

        $this->setElements($elements, false);
        $this->options = $options;
    }

    /**
     * Render specific group .
     *
     * @return mixed
     */
    public function render() {
        $elements = $this->getElements();

        array_walk($elements, function ($element) use (&$result) {
            if( $element->isAllowed() )
                $result .= $element->render();
        });

        return $result;
    }

    public function __toString() {
        return $this->render();
    }

    /**
     * @param $element
     * @return mixed
     */
    public function __get($element) {
        if( $this->hasElement($element) ) {
            $element = $this->getElement($element);

            if( $element->isAllowed() )
                return $element->render();
        }
    }


    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current() {
        $element = current($this->elements);

        return $element;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next() {
        $element = next($this->elements);

        return $element;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    public function key() {
        $element = key($this->elements);

        return $element;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid() {
        $key = key($this->elements);

        $element = ($key !== NULL && $key !== FALSE);

        return $element;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind() {
        reset($this->elements);
    }
}