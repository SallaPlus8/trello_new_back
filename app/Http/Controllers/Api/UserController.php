<?php

namespace App\Http\Controllers\Api;

use App\Service\UserService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\User\UserAddRequest;
use App\Http\Requests\User\UserDeleteRequest;
use App\Http\Requests\User\UserUpdateRequest;

class UserController extends Controller
{
    private $users;

    public function __construct(UserService $users)
    {
        $this->users = $users;

        // $this->middleware('permission:read-users')->only('getUsers');
        // $this->middleware('permission:create-users')->only('create');
        // $this->middleware('permission:update-users')->only('update');
        // $this->middleware('permission:delete-users')->only('delete');
    }
    public function getUsers()
    {
        $users =  $this->users->getUsers();

        return response()->json([
            'data'      => $users,
            'success'   => "true"

        ], 200);
    }
    public function create(UserAddRequest $request)
    {
        $user = $this->users->create($request);

        return response()->json([
            'data'      => $user,
            'success'   => "true"

        ], 201);
    }

    public function update(UserUpdateRequest $request)
    {
        $user = $this->users->update($request);

        return response()->json([
            'data'      => $user,
            'success'   => "true"

        ], 202);
    }
    public function destroy(UserDeleteRequest $request)
    {
        $user = $this->users->destroy($request);

        return response()->json([
            'success'   => "true"

        ], 203);
    }
}
