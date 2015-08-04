<?php

namespace Flysap\Support\Traits;

trait ElementPermissions {

    /**
     * @var
     */
    protected $roles;

    /**
     * @var
     */
    protected $permissions;

    /**
     * Check if current element has roles .
     *
     * @return bool
     */
    public function hasRoles() {
        if( $this->roles )
            return true;

        return false;
    }

    /**
     * Check if current element has permissions ..
     *
     * @return bool
     */
    public function hasPermissions() {
        if( $this->permissions )
            return true;

        return false;
    }

    /**
     * Set roles for element .
     *
     * @param $roles
     * @return $this
     */
    public function roles($roles) {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Set permissions for element.
     *
     * @param $permissions
     * @return $this
     */
    public function permissions($permissions) {
        $this->permissions = $permissions;

        return $this;
    }

    /**
     * Check if current user is allowed to access current eleement .
     *
     * @return bool
     */
    public function isAllowed() {

        /** Check for roles . */
        if( $this->hasRoles() ) {
            if( \Flysap\Users\is( $this->roles ) )
                return true;

            return false;
        }

        /** Check for permissions . */
        if( $this->hasPermissions() ) {
            if( \Flysap\Users\can( $this->permissions ) )
                return true;

            return false;
        }

        return true;
    }
}