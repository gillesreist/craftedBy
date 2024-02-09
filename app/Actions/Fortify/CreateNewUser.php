<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'firstname' => ['required', 'string', 'max:55'],
            'lastname' => ['required', 'string', 'max:55'],
            'birthdate' => ['required', 'date'],
            'phone_number' => ['required', 'string', 'max:55'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
             'password' => $this->passwordRules(),
        ])->validate();


        return User::create([
            'firstname' => $input['firstname'],
            'lastname' => $input['lastname'],
            'birthdate' => $input['birthdate'],
            'role' => $input['role'],
            'phone_number' => $input['phone_number'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);
    }
}
