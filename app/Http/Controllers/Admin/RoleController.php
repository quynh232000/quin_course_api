<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\UserRole;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function roles()
    {
        $roles = Role::all();
        return view("pages.roles", compact('roles'));
    }
    public function createRoles(Request $request)
    {
        if (!$request->name || !$request->description) {
            return redirect()->back()->with("error", "Name and Description are required");
        }
        $role = new Role();
        $role->name = $request->name;
        $role->description = $request->description;
        $role->save();
        return redirect()->back()->with("success", "Role Created Successfully");
    }
    public function delete($id)
    {

        $role = Role::find($id);
        if (!$role) {
            return redirect()->back()->with("error_delete", "Failed to delete role. Role { $id } not fund!");
        }
        $checkUserRole = UserRole::where('role_id', $role->id)->count() ?? 0;
        if ($checkUserRole > 0) {
            return redirect()->back()->with("error_delete", "Failed to delete role. Role { $id } has $checkUserRole users assigned !");
        }

        $role->delete();
        return redirect()->back()->with("success_delete", "Role Deleted Successfully");
    }
}
