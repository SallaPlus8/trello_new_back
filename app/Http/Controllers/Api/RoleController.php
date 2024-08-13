<?php

namespace App\Http\Controllers\Api;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Role\RoleCreateRequest;
use App\Http\Requests\Role\RoleDeleteRequest;
use App\Http\Requests\Role\RoleUpdateRequest;
use App\Service\RoleService;


class RoleController extends Controller
{
    
    private $roles;

    public function __construct(RoleService $role)
    {   

        $this->roles = $role;

        $this->middleware(['permission:read-roles'])->only('getRoles');
        $this->middleware(['permission:delete-roles'])->only('destroy');
        $this->middleware(['permission:update-roles'])->only('update');
        $this->middleware(['permission:create-roles'])->only('create');
    }

    public function getRoles() 
    {
        $roles = $this->roles->getRoles();

        return response()->json([
            'data'      => $roles,

            'success'   => "true"

        ], 200);
    }

    public function create(RoleCreateRequest $request) 
    {   
     

        $role = $this->roles->create($request);

        return response()->json([
            'data'      => $role,
            'success'   => "true"

        ], 201);

    }

    public function update(RoleUpdateRequest $request) 
    {
 
        $role = $this->roles->update($request);

        return response()->json([
            'data'      => $role,
            'success'   => "true"

        ], 202);
    }

    public function destroy(RoleDeleteRequest $request) 
    {
        $role = $this->roles->destroy($request);

        return response()->json([
            'success'   => "true"

        ], 203);

    }

}
