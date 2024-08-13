<?php

namespace App\Service;

use App\Models\Role;
use App\Models\User;

class RoleService 
{

    public function getRoles()
    {
        $roles = Role::all();
        
        return $roles;
    }

    public function create($request) 
    {   
        $validated = $request->validated();
    
        $role = Role::create($validated);

        $role->givePermissions($validated['permissions']);

        $user = User::find($validated['user_id']);
        
        $user->addRole($role);

        return $role;

    }

    public function update($request) 
    {
        $validated = $request->validated();
    
        $role = Role::find($validated['role_id']);

        $role->update($validated);
        
        $role->syncPermissions($validated['permissions']);

        $user = User::find($validated['user_id']);
        
        $user->syncRoles([$role->id]);

        return $role;

    }

    public function destroy($request) 
    {
        $validated = $request->validated();

        $role = Role::find($validated['role_id']);

        $role->delete();

    }
}