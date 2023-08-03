<?php

namespace App\Domains\Auth\Listeners;

use App\Domains\Auth\Events\User\PasswordReset;
use App\Domains\Auth\Events\User\UserCreated;
use App\Domains\Auth\Events\User\UserDeleted;
use App\Domains\Auth\Events\User\UserDestroyed;
use App\Domains\Auth\Events\User\UserLoggedIn;
use App\Domains\Auth\Events\User\UserRestored;
use App\Domains\Auth\Events\User\UserStatusChanged;
use App\Domains\Auth\Events\User\UserUpdated;
use Illuminate\Events\Dispatcher;

class UserEventListener
{
    public function onLoggedIn(UserLoggedIn $event)
    {
        // Update the logging in users time & IP
        $event->user->update([
            'last_login_at' => now(),
            'last_login_ip' => request()->getClientIp(),
        ]);
    }

    public function onPasswordReset(PasswordReset $event)
    {
        $event->user->update([
            'password_changed_at' => now(),
        ]);
    }

    public function onCreated(UserCreated $event)
    {
        activity('user')
            ->performedOn($event->user)
            ->withProperties([
                'user' => [
                    'type' => $event->user->type,
                    'name' => $event->user->name,
                    'email' => $event->user->email,
                    'active' => $event->user->active,
                    'email_verified_at' => $event->user->email_verified_at,
                ],
                'roles' => $event->user->roles->count() ? $event->user->roles->pluck('name')->implode(', ') : 'None',
                'permissions' => $event->user->permissions ? $event->user->permissions->pluck('description')->implode(', ') : 'None',
            ])
            ->log(':causer.name created user :subject.name with roles: :properties.roles and permissions: :properties.permissions');
    }

    public function onUpdated(UserUpdated $event)
    {
        activity('user')
            ->performedOn($event->user)
            ->withProperties([
                'user' => [
                    'type' => $event->user->type,
                    'name' => $event->user->name,
                    'email' => $event->user->email,
                ],
                'roles' => $event->user->roles->count() ? $event->user->roles->pluck('name')->implode(', ') : 'None',
                'permissions' => $event->user->permissions ? $event->user->permissions->pluck('description')->implode(', ') : 'None',
            ])
            ->log(':causer.name updated user :subject.name with roles: :properties.roles and permissions: :properties.permissions');
    }

    public function onDeleted(UserDeleted $event)
    {
        activity('user')
            ->performedOn($event->user)
            ->log(':causer.name deleted user :subject.name');
    }

    public function onRestored(UserRestored $event)
    {
        activity('user')
            ->performedOn($event->user)
            ->log(':causer.name restored user :subject.name');
    }

    public function onDestroyed(UserDestroyed $event)
    {
        activity('user')
            ->performedOn($event->user)
            ->log(':causer.name permanently deleted user :subject.name');
    }

    public function onStatusChanged(UserStatusChanged $event)
    {
        activity('user')
            ->performedOn($event->user)
            ->log(':causer.name '.($event->status === 0 ? 'deactivated' : 'reactivated').' user :subject.name');
    }

    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            UserLoggedIn::class,
            'App\Domains\Auth\Listeners\UserEventListener@onLoggedIn'
        );

        $events->listen(
            PasswordReset::class,
            'App\Domains\Auth\Listeners\UserEventListener@onPasswordReset'
        );

        $events->listen(
            UserCreated::class,
            'App\Domains\Auth\Listeners\UserEventListener@onCreated'
        );

        $events->listen(
            UserUpdated::class,
            'App\Domains\Auth\Listeners\UserEventListener@onUpdated'
        );

        $events->listen(
            UserDeleted::class,
            'App\Domains\Auth\Listeners\UserEventListener@onDeleted'
        );

        $events->listen(
            UserRestored::class,
            'App\Domains\Auth\Listeners\UserEventListener@onRestored'
        );

        $events->listen(
            UserDestroyed::class,
            'App\Domains\Auth\Listeners\UserEventListener@onDestroyed'
        );

        $events->listen(
            UserStatusChanged::class,
            'App\Domains\Auth\Listeners\UserEventListener@onStatusChanged'
        );
    }
}
