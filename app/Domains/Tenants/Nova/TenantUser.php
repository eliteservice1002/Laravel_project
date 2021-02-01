<?php

namespace App\Domains\Tenants\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Text;

// use Vyuldashev\NovaPermission\Permission;
// use Vyuldashev\NovaPermission\PermissionBooleanGroup;
// use Vyuldashev\NovaPermission\Role;
// use Vyuldashev\NovaPermission\RoleBooleanGroup;

class TenantUser extends Resource
{
    public static $model = \App\Domains\Tenants\Models\TenantUser::class;

    public static $title = 'name';

    public function fields(Request $request): array
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),

            Gravatar::make()->maxWidth(50),

            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Email')
                ->sortable()
                ->rules('required', 'email', 'max:254')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}'),

            Password::make('Password')
                ->onlyOnForms()
                ->creationRules('required', 'string', 'min:8')
                ->updateRules('nullable', 'string', 'min:8'),

            // MorphToMany::make('Roles', 'roles', Role::class),
            // MorphToMany::make('Permissions', 'permissions', Permission::class),
            //
            // RoleBooleanGroup::make('Roles'),
            // PermissionBooleanGroup::make('Permissions'),
        ];
    }
}
