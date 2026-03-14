<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function customers()
    {
        $user = Auth::user();
        abort_unless($user->isAdmin(), 403);

        $customers = User::where('role', 'customer')->orderBy('name')->get();
        return view('admin.customers', compact('customers'));
    }

    public function showCustomer(User $customer)
    {
        $user = Auth::user();
        abort_unless($user->isAdmin(), 403);

        abort_unless($customer->role === 'customer', 404);
        return view('admin.customer', compact('customer'));
    }

    public function profile()
    {
        $user = Auth::user();
        abort_unless($user->isAdmin(), 403);
        return view('admin.profile', ['user' => $user]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        abort_unless($user->isAdmin(), 403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'address_line' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zipcode' => 'nullable|string|max:50',
            'country' => 'nullable|string|max:255',
        ]);

        $user->fill($validated);
        $user->save();

        return back()->with('success', 'Dados atualizados.');
    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();
        abort_unless($user->isAdmin(), 403);

        $validated = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if (! Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Senha atual incorreta.']);
        }

        $user->password = Hash::make($validated['new_password']);
        $user->save();

        return back()->with('success', 'Senha alterada com sucesso.');
    }
}
