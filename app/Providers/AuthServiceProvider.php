<?php

namespace App\Providers;

use App\Permission;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        parent::registerPolicies($gate);

        // Dynamically register permissions with Laravel's Gate.
        foreach ($this->getPermissions() as $permission) {
            $gate->define($permission->name, function ($user, $post=null) use ($permission) {
                // Check if user is super Admin
                if ($user->roles[0]->name == "admin") {
                    return true;
                }

                // dd($permission->name);
                // Check if user is owner of post
                if ($permission->name == "edit_post") {
                    // dd($user->id);
                    return $user->id == $post->user_id;
                }

                return $user->hasPermission($permission);
            });
        }
    }

    /**
     * Fetch the collection of site permissions.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getPermissions()
    {
        // dd(Permission::with('roles')->get());
        return Permission::with('roles')->get();
    }
}
