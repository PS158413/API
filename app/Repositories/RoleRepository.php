<?php

namespace App\Repositories;

use App\Interfaces\CrudInterface;
use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;

class RoleRepository implements CrudInterface
{
    /**
     * Authenticated User Instance.
     *
     * @var User
     */
    public User|null $user;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->user = Auth::guard()->user();
    }

    /**
     * Get All Roles.
     *
     * @return collections Array of Product Collection
     */
    public function getAll(): Paginator
    {
        if (!$this->user) {
            return User::query()->paginate(10);
        }

        return $this->user->roles()
            ->orderBy('id', 'asc')
            ->with('user')
            ->paginate(10);
    }

    /**
     * Get Paginated Role Data.
     *
     * @param  int  $pageNo
     * @return collections Array of Role Collection
     */
    public function getPaginatedData($perPage): Paginator
    {
        $perPage = isset($perPage) ? intval($perPage) : 10;

        return Role::orderBy('id', 'asc')->with('user')->paginate($perPage);

    }

    /**
     * Create New Role.
     *
     * @return object Role Object
     */
    public function create(array $data): Role
    {
        $user = Auth::user();
        $data['user_id'] = $user->id;

        return Role::create($data);
    }

    /**
     * Delete Role.
     *
     * @return bool true if deleted otherwise false
     */
    public function delete(int $id): bool
    {
        $role = Role::find($id);
        if (empty($role)) {
            return false;
        }
        $role->delete($role);

        return true;
    }

    /**
     * Get Role Detail By ID.
     *
     * @return void
     */
    public function getByID(int $id): Role|null
    {
        return Role::with('user')->find($id);
    }

    /**
     * Update Role By ID.
     *
     * @return object Updated Role Object
     */
    public function update(int $id, array $data)
    {
        $role = Role::find($id);

        // If everything is OK, then update.
        $role->update($data);

        // Finally return the updated staff.
        return $this->getByID($role->id);
    }

    /**
     * Get the cashier number for a user.
     */
    public function getCashierNumber(User $user): string
    {
        return 'K' . str_pad($user->id, 4, '0', STR_PAD_LEFT);
    }


    /**
     * Update Role from user By ID.
     *
     * @return object Updated Role Object
     */
    public function update_user_role(int $id, array $data)
    {
        $user = User::find($id);

        // If the user is not found, return null
        if (is_null($user)) {
            return null;
        }

        // Update the user's role if provided
        if (isset($data['name'])) {
            $newRole = $data['name'];
            $currentRole = $user->role->first()->name ?? null;

            // Check if the user's current role is "kassamedewerker"
            $isCurrentRoleCashier = $currentRole === 'kassamedewerker';

            // Check if the new role is "kassamedewerker"
            $isNewRoleCashier = $newRole === 'kassamedewerker';

            if (!$isCurrentRoleCashier && $isNewRoleCashier) {
                // Updating from a different role to "kassamedewerker"
                $user->cashier_number = $this->getCashierNumber($user);
            } elseif ($isCurrentRoleCashier && !$isNewRoleCashier) {
                // Updating from "kassamedewerker" to a different role
                $user->cashier_number = null;
            }

            $role = Role::where('name', $newRole)->first();

            if ($role) {
                $user->role()->sync([$role->id]);
            }
        }

        // Save only the role changes to the database
        $user->save();

        // Return the updated user with the role
        return $user->load('role');
    }
}
