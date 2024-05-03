<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use App\Models\Address;


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
            'first_address_line' => ['required', 'string', 'max:255'],
            'second_address_line' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'max:55'],
            'city' => ['required', 'string', 'max:255'],
            'password' => $this->passwordRules(),
        ])->validate();


        $user =  User::create([
            'firstname' => $input['firstname'],
            'lastname' => $input['lastname'],
            'birthdate' => $input['birthdate'],
            'phone_number' => $input['phone_number'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);

        Address::create([
            'user_id' => $user->id,
            'name' => 'standard',
            'type' => 'BOTH',
            'firstname' => $input['firstname'],
            'lastname' => $input['lastname'],
            'first_address_line' => $input['first_address_line'],
            'second_address_line' => $input['second_address_line'],
            'postal_code' => $input['postal_code'],
            'city' => $input['city'],
        ]);

        return $user;
    }
}
