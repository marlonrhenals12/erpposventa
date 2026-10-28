<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class RegistroUsuarioController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        if (! DB::table('registro_publico')
            ->where('id', 1)
            ->where('completado', false)
            ->exists()) {
            abort(404);
        }

        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $usuario = DB::transaction(function () use ($request): Usuario {
            $registro = DB::table('registro_publico')
                ->where('id', 1)
                ->lockForUpdate()
                ->first();

            abort_if(! $registro || $registro->completado, 403);

            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|lowercase|email|max:255|unique:'.Usuario::class,
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $usuario = Usuario::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $usuario->asignarRol('admin');

            DB::table('registro_publico')
                ->where('id', 1)
                ->update(['completado' => true, 'updated_at' => now()]);

            return $usuario;
        });

        event(new Registered($usuario));

        Auth::login($usuario);
        $request->session()->regenerate();

        return redirect(route('dashboard', absolute: false));
    }
}
