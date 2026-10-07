<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    /**
     * Display users list with their assigned roles.
     */
    public function index(): Response
    {
        $users = User::with('roles')->latest()->get()->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at ? $user->created_at->format('d/m/Y') : '',
                'roles' => $user->roles->map(fn ($r) => [
                    'id' => $r->id,
                    'name' => $r->name,
                    'display_name' => $r->display_name,
                ]),
                'role_ids' => $user->roles->pluck('id')->toArray(),
            ];
        });

        $roles = Role::all(['id', 'name', 'display_name', 'description']);

        return Inertia::render('Configuracion/UsuariosRoles', [
            'users' => $users,
            'roles' => $roles,
            'flash' => [
                'success' => session('success'),
                'error' => session('error'),
            ],
        ]);
    }

    /**
     * Store a newly created user with assigned roles.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,id',
        ], [
            'roles.required' => 'Debes asignar al menos un rol al usuario.',
            'roles.min' => 'Debes asignar al menos un rol al usuario.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->roles()->sync($validated['roles']);

        return redirect()->back()->with('success', 'Usuario creado y roles asignados exitosamente.');
    }

    /**
     * Update user details and synchronize roles.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:6',
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,id',
        ], [
            'roles.required' => 'Debes asignar al menos un rol al usuario.',
            'roles.min' => 'Debes asignar al menos un rol al usuario.',
        ]);

        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        if (! empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }

        $user->update($userData);
        $user->roles()->sync($validated['roles']);

        return redirect()->back()->with('success', 'Usuario y roles actualizados correctamente.');
    }

    /**
     * Delete user from system.
     */
    public function destroy(User $user, Request $request)
    {
        if ($request->user()->id === $user->id) {
            return redirect()->back()->with('error', 'No puedes eliminar tu propio usuario de sesión.');
        }

        $user->roles()->detach();
        $user->delete();

        return redirect()->back()->with('success', 'Usuario eliminado correctamente.');
    }
}
