<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('role');

        // Filter by search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by role
        if ($request->filled('role_id')) {
            $query->where('role_id', $request->role_id);
        }

        // Filter by status
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $data = [
            'users' => $query->latest()->paginate(15)->withQueryString(),
            'roles' => Role::all(),
            'filters' => $request->only(['search', 'role_id', 'is_active']),
        ];

        return view('backend.settings.users.index', $data);
    }

    public function create()
    {
        $data = [
            'roles' => Role::all(),
        ];

        return view('backend.settings.users.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'phone' => 'nullable|max:20',
            'avatar' => 'nullable|image|max:2048',
        ]);

        $userData = $request->only(['name', 'email', 'password', 'role_id', 'phone']);
        $userData['is_active'] = $request->boolean('is_active', true);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $userData['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user = User::create($userData);
        ActivityLog::log('create', "Menambah user {$user->name}", $user);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil ditambahkan');
    }

    public function show(User $user)
    {
        $data = [
            'user' => $user->load(['role', 'activityLogs' => fn($q) => $q->latest()->take(10)]),
        ];

        return view('backend.settings.users.show', $data);
    }

    public function edit(User $user)
    {
        $data = [
            'user' => $user,
            'roles' => Role::all(),
        ];

        return view('backend.settings.users.edit', $data);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'phone' => 'nullable|max:20',
            'avatar' => 'nullable|image|max:2048',
        ]);

        $userData = $request->only(['name', 'email', 'role_id', 'phone']);
        $userData['is_active'] = $request->boolean('is_active', true);

        // Handle password
        if ($request->filled('password')) {
            $userData['password'] = $request->password;
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $userData['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($userData);
        ActivityLog::log('update', "Mengubah user {$user->name}", $user);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil diperbarui');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'Tidak dapat menghapus akun sendiri');
        }

        // Delete avatar
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        ActivityLog::log('delete', "Menghapus user {$user->name}", $user);
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User berhasil dihapus');
    }
}
