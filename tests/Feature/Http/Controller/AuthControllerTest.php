<?php

use App\Models\User;
use Laravel\Lumen\Testing\DatabaseMigrations;

class AuthControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testUserShouldNotAuthenticateWithoutCredentials(): void
    {
        $this->post(route('auth.login'));

        $this->assertResponseStatus(422);
        $this->seeJsonContains(['email' => ['The email field is required.']]);
        $this->seeJsonContains(['password' => ['The password field is required.']]);
    }

    public function testUserShouldBeDeniedIfNotRegistered(): void
    {
        $payload = [
            'email' => 'rand@email.com',
            'password' => 'teste',
        ];

        $this->post(route('auth.login'), $payload);

        $this->assertResponseStatus(401);
        $this->seeJsonStructure(['error', 'message', 'code']);
        $this->seeJsonEquals([
            'error' => true,
            'message' => 'Wrong credentials.',
            'code' => 401,
        ]);
    }

    public function testUserShouldBeDeniedIfSendWrongPassword(): void
    {
        $user = User::factory()->create();

        $payload = [
            'email' => $user->email,
            'password' => 'teste',
        ];

        $this->post(route('auth.login'), $payload);

        $this->assertResponseStatus(401);
        $this->seeJsonStructure(['error', 'message', 'code']);
        $this->seeJsonEquals([
            'error' => true,
            'message' => 'Wrong credentials.',
            'code' => 401,
        ]);
    }

    public function testUserCanAuthenticate(): void
    {
        $this->artisan('passport:install');

        $user = User::factory()->create();

        $payload = [
            'email' => $user->email,
            'password' => 'secret123',
        ];

        $this->post(route('auth.login'), $payload);

        $this->assertResponseOk();
        $this->seeJsonStructure(['access_token', 'expires_at']);
    }
}
