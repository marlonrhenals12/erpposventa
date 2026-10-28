<?php

namespace Tests\Feature\Auth;

use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertInertia(fn (Assert $page) => $page
            ->component('Auth/Register'));
        $this->get('/login')->assertInertia(fn (Assert $page) => $page
            ->where('canRegister', true));
    }

    public function test_new_users_can_register(): void
    {
        $usuarioPrueba = Usuario::factory()->create(['email' => 'admin@posventa.com']);
        $usuarioPrueba->asignarRol('admin');

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect(route('dashboard', absolute: false));
        $usuario = Usuario::where('email', 'test@example.com')->firstOrFail();
        $this->assertAuthenticatedAs($usuario);
        $this->assertDatabaseHas('roles', ['nombre' => 'admin']);
        $this->assertTrue($usuario->tieneRol('admin'));
        $this->assertFalse($usuario->tieneRol('vendedor'));

        $this->post('/logout');
        $this->get('/login')->assertInertia(fn (Assert $page) => $page
            ->where('canRegister', false));
        $this->get('/register')->assertNotFound();
        $this->post('/register', [
            'name' => 'Another User',
            'email' => 'another@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertForbidden();

        $this->assertDatabaseMissing('usuarios', ['email' => 'another@example.com']);
    }
}
