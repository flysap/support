<?php

namespace Flysap\Support\Traits;

use Flysap\Support\Group;

trait ElementsGroup {

    /**
     * @var
     */
    protected $groups = [];

    /**
     * Adding elements to special group .
     *
     * @param string $name
     * @param array $elements
     * @param array $options
     * @return $this
     */
    public function addGroup($name = Group::DEFAULT_GROUP_NAME, $elements = array(), array $options = array()) {
        if(! is_array($elements))
            $elements = [$elements];

        if( $this->hasGroup($name) )
            $this->groups[$name]
                ->addElements($elements);
        else
            $this->groups[$name] = (new Group($elements, $options));

        return $this;
    }

    /**
     * Get group by name .
     *
     * @param $name
     * @return bool
     */
    public function getGroup($name) {
        if( $this->hasGroup($name) )
            return $this->groups[$name];

        return false;
    }

    /**
     * Check if has group .
     *
     * @param $name
     * @return bool
     */
    public function hasGroup($name) {
        if( ! array_key_exists($name, $this->groups) )
            return false;

        return true;
    }

    /**
     * Get all groups .
     *
     * @return array
     */
    public function getGroups() {
        return $this->groups;
    }

    /**
     * Remove group .
     *
     * @param $name
     * @return $this
     */
    public function removeGroup($name) {
        if( $this->hasGroup($name) )
            unset($this->groups[$name]);

        return $this;
    }
}