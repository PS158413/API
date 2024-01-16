<?php

namespace App\Repositories;

use App\Interfaces\CrudInterface;
use App\Models\Role;
use App\Models\User;
//use Your Model
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Class SickuserRepository.
 */
class UserRepository implements CrudInterface
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
     * Get All Users.
     *
     * @return \Illuminate\Contracts\Pagination\Paginator Array of User Collection
     */
    public function getAll(): Paginator
    {
        $users = User::where('id', '!=', 1)->orderBy('id', 'asc')->with('role')->paginate(10);

        return $users;
    }

    /**
     * Get Paginated User Data.
     *
     * @param  int  $pageNo
     * @return collections Array of User Collection
     */
    public function getPaginatedData($perPage): Paginator
    {
        $perPage = isset($perPage) ? intval($perPage) : 10;

        return User::orderBy('id', 'asc')->with('role')->paginate($perPage);
    }

    /**
     * Get the cashier number for a user.
     */
    public function getCashierNumber(User $user): string
    {
        return 'K' . str_pad($user->id, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Create New User.
     *
     * @return object User Object
     */
    public function create(array $data): User
    {
        $birthday = date('Y-m-d', strtotime($data['birthday']));

        $userData = [
            'name' => $data['name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'city' => $data['city'],
            'phone' => $data['phone'],
            'birthday' => $birthday,
        ];

        $role = Role::where('name', $data['role'])->first();

        if ($role && $role->name === 'kassamedewerker') {
            $user = new User($userData);
            $user->save();

            $user->cashier_number = $this->getCashierNumber($user);

            if ($user->save()) {
                $user->role()->attach($role);
            }
        } else {
            $user = User::create($userData);
            $user->role()->attach($role);
        }

        return $user;
    }

    /**
     * Delete User.
     *
     * @return bool true if deleted otherwise false
     */
    public function delete(int $id): bool
    {
        $User = User::find($id);
        if (empty($User)) {
            return false;
        }
        $User->delete($User);

        return true;
    }

    /**
     * Get User Detail By ID.
     *
     * @return void
     */
    public function getByID(int $id): User|null
    {
        return User::with('user')->with('role')->find($id);
    }

    /**
     * Update User By ID.
     *
     * @return object Updated User Object
     */
    public function update(int $id, array $data)
    {

        $user = User::find($id);

        // If the user is not found, return null
        if (is_null($user)) {
            return null;
        }

        // Convert the date format from "dd-mm-yyyy" to "yyyy-mm-dd"
        if (isset($data['birthday'])) {
            $data['birthday'] = date('Y-m-d', strtotime($data['birthday']));
        }

        // Update the user data
        $user->update($data);

        // Update the user's role if provided
        if (isset($data['role'])) {
            $role = Role::where('name', $data['role'])->first();

            if ($role) {
                $user->role()->sync([$role->id]);
            }
        }

        // Return the updated user with the role
        return $user->load('role');
    }
}
