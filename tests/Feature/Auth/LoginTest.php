<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;
use Tests\Traits\LoggedIn;

class LoginTest extends TestCase
{
    use RefreshDatabase, LoggedIn;

    public function test_login_screen_can_be_rendered() : void
    {
        $response = $this->get(route('login'));
        $response->assertStatus(200);
    }

    public function test_user_can_login_successfully() : void
    {
        $user = User::factory(['email' => 'desafio@example.com', 'password' => Hash::make('123456')])->create();

        $this->post(route('login'), ['email' => $user->email, 'password' => '123456'])
            ->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_user_doest_not_login_if_password_incorrect() : void
    {
        $this
            ->withoutExceptionHandling()
            ->expectException(ValidationException::class);

        $user = User::factory(['email' => 'desafio@example.com', 'password' => Hash::make('123456')])->create();

        $this->post(route('login'), ['email' => $user->email, 'password' => '9'])
            ->assertRedirect();

        $this->assertGuest();
    }

    public function test_user_can_logout() : void
    {
        $this->login();
        $this->post(route('logout'))->assertRedirect(route('login'));
        $this->assertGuest();
    }

}
