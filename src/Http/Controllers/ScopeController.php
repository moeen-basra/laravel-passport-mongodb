<?php

namespace MoeenBasra\LaravelPassportMongoDB\Http\Controllers;

use MoeenBasra\LaravelPassportMongoDB\Passport;

class ScopeController
{
    /**
     * Get all of the available scopes for the application.
     *
     * @return \Illuminate\Support\Collection
     */
    public function all()
    {
        return Passport::scopes();
    }
}
