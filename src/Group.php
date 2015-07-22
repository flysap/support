<?php

namespace Flysap\Support;

use Flysap\Support\Traits\ElementAttributes;
use Flysap\Support\Traits\ElementsTrait;

class Group {

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

}