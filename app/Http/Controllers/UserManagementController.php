<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $users = User::orderBy('registration_date', 'desc')->paginate(10);
        $roles = Role::all();

        // Check if a specific user ID is requested (from notification click)
        if ($request->has('user') && $request->user) {
            $specificUser = User::find($request->user);
            if ($specificUser) {
                // You could highlight the user or scroll to it, but for now just ensure it's in the results
                // Since we're paginating, we might need to adjust the page
                $userPage = ceil(User::where('registration_date', '>=', $specificUser->registration_date)->count() / 10);
                $users = User::orderBy('registration_date', 'desc')->paginate(10, ['*'], 'page', $userPage);
            }
        }

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

        // Mark related notifications as read
        $user->notifications()
            ->where('data->user_id', $user->id)
            ->where('data->type', 'new_user_registration')
            ->update(['read_at' => now()]);

        // Notify the user about approval
        $user->notify(new \App\Notifications\UserRegistrationApprovalNotification(true, $user->requested_role));

        return redirect()->back()->with('success', 'User approved and role assigned successfully!');
    }

    public function reject(Request $request, User $user)
    {
        $user->update(['status' => 'rejected']);

        // Notify the user about rejection
        $user->notify(new \App\Notifications\UserRegistrationApprovalNotification(false, $user->requested_role ?? 'user'));

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
            $oldEmail = $user->email;
            $newEmail = $user->requested_email;

            $user->email = $user->requested_email;
            $user->requested_email = null;
            $user->email_verified_at = null; // Reset verification
            $user->save();

            // Mark related notifications as read
            $user->notifications()
                ->where('data->user_id', $user->id)
                ->where('data->type', 'email_change_request')
                ->update(['read_at' => now()]);

            // Notify the user about the approval
            $user->notify(new \App\Notifications\EmailChangeApprovalNotification(true, $newEmail));

            return redirect()->back()->with('success', 'Email change approved successfully.');
        }

        return redirect()->back()->with('error', 'No email change request found.');
    }

    public function rejectEmailChange(Request $request, User $user)
    {
        $requestedEmail = $user->requested_email;

        $user->requested_email = null;
        $user->save();

        // Mark related notifications as read
        $user->notifications()
            ->where('data->user_id', $user->id)
            ->where('data->type', 'email_change_request')
            ->update(['read_at' => now()]);

        // Notify the user about the rejection
        if ($requestedEmail) {
            $user->notify(new \App\Notifications\EmailChangeApprovalNotification(false, $requestedEmail));
        }

        return redirect()->back()->with('success', 'Email change request rejected.');
    }
}
