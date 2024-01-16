<?php

namespace App\Repositories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthRepository
{
    /**
     * Get the customer number for a user.
     */

    /**
     * Get the customer number for a user.
     */
    public function generateUniqueCustomerNumber(): string
    {
        $unique = false;
        $customerNumber = '';

        while (! $unique) {
            $randomNumber = str_pad(random_int(0, 99999999), 8, '0', STR_PAD_LEFT);
            $customerNumber = $randomNumber;

            // Check if the customer number already exists in the database
            $existingUser = User::where('customernumber', $customerNumber)->first();
            if (! $existingUser) {
                $unique = true;
            }
        }

        return $customerNumber;
    }

    public function register(array $data): User
    {
        $userData = [
            'name' => $data['name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'city' => $data['city'],
            'phone' => $data['phone'],
            'birthday' => $data['birthday'],
        ];

        $user = User::create($userData);

        // Assign the "user" role to the user
        $role = Role::where('name', 'user')->first(); // Assuming the "user" role exists in the roles table
        if ($role) {
            $user->role()->attach($role);
        }

        $user->customernumber = $this->generateUniqueCustomerNumber();
        $user->save();

        return $user;
    }
}
