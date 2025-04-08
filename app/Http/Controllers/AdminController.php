<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::with(['wallet', 'role'])->get();
        $transactions = Transaction::all();
        return view('admin.dashboard', compact('users', 'transactions'));
    }

    public function createPage() {
        $roles = Role::all();
        return view('admin.createuser', compact('roles'));
    }

    public function store(Request $request) {

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'role_id' => 'required|exists:roles,id'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role_id' =>$request->role_id
        ]);

        Wallet::create([
            'user_id' => $user->id,
            'balance'=> 0,
            'wallet_number' => fake()->unique()->numerify('##########')
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'User Berhasil ditambahkan.');
    }

    public function editPage(User $user) {
        $roles = Role::all();
        return view('admin.edituser', compact('user', 'roles'));
    }

    public function update(Request $request, User $user) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'role_id' => 'required|exists:roles,id'
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'User diupdate.');
    }

    public function destroy(User $user) {
        $user->delete();
        return redirect()->route('admin.dashboard')->with('success', 'User dihapus.');
    }
}
