<?php

namespace Flysap\Support\Traits;

use Illuminate\Support\Facades\Auth;

trait ElementPermissions {

    /**
     * @var
     */
    protected $roles = [];

    /**
     * @var
     */
    protected $permissions = [];

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
     * Check if current user is allowed to access current element .
     *
     * @param array $roles
     * @param array $permissions
     * @return bool
     */
    public function isAllowed(array $roles = [], array $permissions = []) {
        $roles        = $roles ?: $this->roles;
        $permissions  = $permissions ?: $this->permissions;

        /** Check for roles . */
        if( $roles ) {
            if( $this->is( $roles ) )
                return true;

            return false;
        }

        /** Check for permissions . */
        if( $permissions ) {
            if( $this->can( $permissions ) )
                return true;

            return false;
        }

        return true;
    }


    /**
     * Have permission to .
     *
     * @param $permission
     * @return bool
     * @internal param $role
     */
    protected function can($permission) {
        if(Auth::check() && Auth::user()->can($permission))
            return true;

        return false;
    }

    /**
     * Check if current is role .
     *
     * @param $role
     * @return bool
     */
    protected function is($role) {
        if(Auth::check() && Auth::user()->is($role))
            return true;

        return false;
    }
}