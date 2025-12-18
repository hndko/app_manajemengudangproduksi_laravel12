<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->latest()->paginate(20);
        return view('settings.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('settings.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(8)],
            'role_id' => 'required|exists:roles,id',
            'phone' => 'nullable|max:20',
            'avatar' => 'nullable|image|max:2048',
        ]);

        $data = $request->except(['password', 'password_confirmation', 'avatar']);
        $data['password'] = Hash::make($request->password);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user = User::create($data);
        ActivityLog::log('create', "Menambah user {$user->name}", $user);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan');
    }

    public function show(User $user)
    {
        $user->load(['role', 'activityLogs' => fn($q) => $q->latest()->take(10)]);
        return view('settings.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('settings.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role_id' => 'required|exists:roles,id',
            'phone' => 'nullable|max:20',
            'avatar' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['name', 'email', 'role_id', 'phone']);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['confirmed', Password::min(8)],
            ]);
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);
        ActivityLog::log('update', "Mengubah user {$user->name}", $user);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri');
        }

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        ActivityLog::log('delete', "Menghapus user {$user->name}", $user);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus');
    }
}
