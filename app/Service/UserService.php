<?php

namespace App\Service;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{


    public function getUsers()
    {
        $users = User::get();

        return $users;
    }

    public function create($request)
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->addMedia($validated['photo'])->toMediaCollection('avatar');

        return $user;
    }

    public function update($request)
    {
        $validated = $request->validated();

        $user = User::find($validated['user_id']);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        if ($validated['photo']) {

            $image = $user->getMedia('avatar');

            $image[0]->delete();

            $user->addMedia($validated['photo'])->toMediaCollection('avatar');
        }

        return $user;
    }

    public function destroy($request)
    {
        $validated = $request->validated();

        $user = User::find($validated['user_id']);

        $user->delete();

        return $user;
    }
}
