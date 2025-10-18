<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::orderBy('registration_date', 'desc')->paginate(10);
        $roles = Role::all();
        return view('admin.users.index', compact('users', 'roles'));
    }

    public function assignRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|exists:roles,name',
        ]);

        // Prevent changing the role of admin users
        if ($user->hasRole('admin')) {
            return redirect()->back()->with('error', 'Admin role cannot be changed.');
        }

        $user->syncRoles([$request->role]);

        return redirect()->back()->with('success', 'Role assigned successfully!');
    }

    public function approve(Request $request, User $user)
    {
        $user->update(['status' => 'approved']);

        // Assign the requested role
        if ($user->requested_role) {
            $user->syncRoles([$user->requested_role]);
        }

        return redirect()->back()->with('success', 'User approved and role assigned successfully!');
    }

    public function reject(Request $request, User $user)
    {
        $user->update(['status' => 'rejected']);

        return redirect()->back()->with('success', 'User rejected successfully!');
    }

    public function removeRole(User $user)
    {
        // Prevent removing role from admin users
        if ($user->hasRole('admin')) {
            return redirect()->back()->with('error', 'Admin role cannot be removed.');
        }

        $user->syncRoles([]);
        return redirect()->back()->with('success', 'Role removed successfully!');
    }

    public function approveEmailChange(Request $request, User $user)
    {
        if ($user->requested_email) {
            $user->email = $user->requested_email;
            $user->requested_email = null;
            $user->email_verified_at = null; // Reset verification
            $user->save();

            return redirect()->back()->with('success', 'Email change approved successfully.');
        }

        return redirect()->back()->with('error', 'No email change request found.');
    }

    public function rejectEmailChange(Request $request, User $user)
    {
        $user->requested_email = null;
        $user->save();

        return redirect()->back()->with('success', 'Email change request rejected.');
    }
}
