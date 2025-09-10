<?php

// app/Http/Controllers/Admin/UserController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $q = User::query()->latest();

        if ($s = trim($request->input('s', ''))) {
            $q->where(function ($qq) use ($s) {
                $qq->where('name','like',"%{$s}%")
                   ->orWhere('email','like',"%{$s}%");
            });
        }
        if ($role = $request->input('role')) {
            $q->where('role', $role);
        }

        $users = $q->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function updateRole(Request $request, User $user)
    {
        // no permitir que el admin se quite su propio rol
        abort_if($request->user()->id === $user->id, 403, 'No puedes cambiar tu propio rol.');

        $validated = $request->validate([
            'role' => ['required','in:user,admin'],
        ]);

        $user->update(['role' => $validated['role']]);

        return back()->with('status', 'Rol actualizado.');
    }

    public function toggleActive(Request $request, User $user)
    {
        abort_if($request->user()->id === $user->id, 403, 'No puedes desactivarte a ti mismo.');

        $user->is_active = ! $user->is_active;
        $user->save();

        return back()->with('status', $user->is_active ? 'Usuario activado.' : 'Usuario desactivado.');
    }

    public function destroy(Request $request, User $user)
    {
        abort_if($request->user()->id === $user->id, 403, 'No puedes eliminarte a ti mismo.');

        // TODO(opcional): borrar/transferir posts, comments, etc.
        $user->delete();

        return back()->with('status', 'Usuario eliminado.');
    }
}
