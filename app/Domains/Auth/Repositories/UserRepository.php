<?php

namespace App\Domains\Auth\Repositories;

use App\Domains\Auth\Events\User\UserCreated;
use App\Domains\Auth\Events\User\UserStatusChanged;
use App\Domains\Auth\Events\User\UserUpdated;
use App\Domains\Auth\Models\User;
use App\Exceptions\GeneralException;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Hash;

class UserRepository extends BaseRepository
{
    protected $model = User::class;

    public function registerProvider($info, $provider): User
    {
        $user = $this->findOneByPrimary($info->id);

        if (! $user) {
            $user = $this->createOrUpdateFromArray([
                'name' => $info->name,
                'email' => $info->email,
                'provider' => $provider,
                'provider_id' => $info->id,
                'email_verified_at' => now(),
            ]);
        }

        return $user;
    }

    /**
     * Create or update a model instance from data array.
     * The use of this method suppose that your array is correctly formatted.
     */
    public function createOrUpdateFromArray(
        array $data,
        bool $saveMissingModelFillableAttributesToNull = true
    ): User {
        $email_verified = isset($data['email_verified']) && $data['email_verified'] === '1' ? now() : null;
        $active = isset($data['active']) && $data['active'] === '1';

        $user = parent::createOrUpdateFromArray(array_merge($data, [
            'email_verified_at' => $email_verified,
            'active' => $active,
        ]), $saveMissingModelFillableAttributesToNull);

        $user->syncRoles($data['roles'] ?? []);

        if (! config('template.access.user.only_roles')) {
            $user->syncPermissions($data['permissions'] ?? []);
        }

        event(new UserCreated($user));

        // They didn't want to auto verify the email, but do they want to send the confirmation email to do so?
        // phpcs:disable
        if (! isset($data['email_verified']) && isset($data['send_confirmation_email']) && $data['send_confirmation_email'] === '1') {
            $user->sendEmailVerificationNotification();
        }

        return $user;
    }

    /**
     * Update a model instance from its primary key.
     */
    public function updateByPrimary(
        int $primary,
        array $data,
        bool $saveMissingModelFillableAttributesToNull = true
    ): User {
        $user = $this->findOneByPrimary($primary);
        $type = $user->isMasterAdmin() ? User::TYPE_ADMIN : $data['type'] ?? $user->type;

        $user = parent::updateByPrimary($primary, array_merge($data, [
            'type' => $type,
        ]), $saveMissingModelFillableAttributesToNull);

        if (! $user->isMasterAdmin()) {
            // Replace selected roles/permissions
            $user->syncRoles($data['roles'] ?? []);

            if (! config('template.access.user.only_roles')) {
                $user->syncPermissions($data['permissions'] ?? []);
            }
        }

        event(new UserUpdated($user));

        return $user;
    }

    public function updateProfile(User $user, array $data = []): User
    {
        $user->name = $data['name'] ?? null;

        if ($user->canChangeEmail() && $user->email !== $data['email']) {
            $user->email = $data['email'];
            $user->email_verified_at = null;
            $user->sendEmailVerificationNotification();
            session()->flash('resent', true);
        }

        return tap($user)->save();
    }

    public function updatePassword(User $user, array $data, $expired = false): User
    {
        if (isset($data['current_password'])) {
            throw_if(
                ! Hash::check($data['current_password'], $user->password),
                new GeneralException(__('That is not your old password.'))
            );
        }

        // Reset the expiration clock
        if ($expired) {
            $user->password_changed_at = now();
        }

        $user->password = $data['password'];

        return tap($user)->update();
    }

    public function mark(User $user, bool $status): User
    {
        if ($status === false && auth()->id() === $user->id) {
            throw new GeneralException(__('You can not do that to yourself.'));
        }

        if ($status === false && $user->isMasterAdmin()) {
            throw new GeneralException(__('You can not deactivate the administrator account.'));
        }

        $user->active = $status;

        if ($user->save()) {
            event(new UserStatusChanged($user, $status));

            return $user;
        }

        throw new GeneralException(__('There was a problem updating this user. Please try again.'));
    }
}
