<?php

namespace App\Filters;

use App\User;
use App\Filters\Filters;

class ThreadFilters extends Filters
{
    protected $filters = ['by'];

    /**
     * Filter the query by a given username.
     * 
     * @param string $username
     */
    public function by($username)
    {
        // if request('by'), we should filter by the given username.
        // We apply our filters to the builder
        $user = User::where('name', $username)->firstOrFail();

        return $this->builder->where('user_id', $user->id);
    }
}
