<?php

namespace App\Domains\Auth\Listeners;

use App\Domains\Auth\Events\Role\RoleCreated;
use App\Domains\Auth\Events\Role\RoleDeleted;
use App\Domains\Auth\Events\Role\RoleUpdated;
use Illuminate\Events\Dispatcher;

class RoleEventListener
{
    public function onCreated(RoleCreated $event)
    {
        activity('role')
            ->performedOn($event->role)
            ->withProperties([
                'role' => [
                    'type' => $event->role->type,
                    'name' => $event->role->name,
                ],
                'permissions' => $event->role->permissions->count() ? $event->role->permissions->pluck('description')->implode(', ') : 'None',
            ])
            ->log(':causer.name created role :subject.name with permissions: :properties.permissions');
    }

    public function onUpdated(RoleUpdated $event)
    {
        activity('role')
            ->performedOn($event->role)
            ->withProperties([
                'role' => [
                    'type' => $event->role->type,
                    'name' => $event->role->name,
                ],
                'permissions' => $event->role->permissions->count() ? $event->role->permissions->pluck('description')->implode(', ') : 'None',
            ])
            ->log(':causer.name updated role :subject.name with permissions: :properties.permissions');
    }

    public function onDeleted(RoleDeleted $event)
    {
        activity('role')
            ->performedOn($event->role)
            ->log(':causer.name deleted role :subject.name');
    }

    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            RoleCreated::class,
            'App\Domains\Auth\Listeners\RoleEventListener@onCreated'
        );

        $events->listen(
            RoleUpdated::class,
            'App\Domains\Auth\Listeners\RoleEventListener@onUpdated'
        );

        $events->listen(
            RoleDeleted::class,
            'App\Domains\Auth\Listeners\RoleEventListener@onDeleted'
        );
    }
}
