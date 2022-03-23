<?php

use App\Models\User;
use Laravel\Lumen\Testing\DatabaseMigrations;

class AuthControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testShouldNotRegisterNewUserWithoutRequiredFields(): void
    {
        $this->post(route('auth.register'), []);

        $this->assertResponseStatus(422);
        $this->seeJsonContains(['name' => ['The name field is required.']]);
        $this->seeJsonContains(['email' => ['The email field is required.']]);
        $this->seeJsonContains(['password' => ['The password field is required.']]);
    }

    public function testUserCanRegister(): void
    {
        $payload = [
            'name' => 'Teste',
            'email' => 'teste@email.com',
            'password' => 'secret123'
        ];

        $this->post(route('auth.register'), $payload);

        $this->assertResponseStatus(201);
        $this->seeJsonContains(['name' => $payload['name']]);
        $this->seeJsonContains(['email' => $payload['email']]);
        $this->seeInDatabase('users', ['email' => $payload['email']]);
    }

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
            'password' => 'teste'
        ];

        $this->post(route('auth.login'), $payload);

        $this->assertResponseStatus(401);
        $this->seeJsonStructure(['error', 'message', 'code']);
        $this->seeJsonEquals([
            'error' => true,
            'message' => 'Wrong credentials.',
            'code' => 401
        ]);
    }

    public function testUserShouldBeDeniedIfSendWrongPassword(): void
    {
        $user = User::factory()->create();

        $payload = [
            'email' => $user->email,
            'password' => 'teste'
        ];

        $this->post(route('auth.login'), $payload);

        $this->assertResponseStatus(401);
        $this->seeJsonStructure(['error', 'message', 'code']);
        $this->seeJsonEquals([
            'error' => true,
            'message' => 'Wrong credentials.',
            'code' => 401
        ]);
    }

    public function testUserCanAuthenticate(): void
    {
        $this->artisan('passport:install');

        $user = User::factory()->create();

        $payload = [
            'email' => $user->email,
            'password' => 'secret123'
        ];

        $this->post(route('auth.login'), $payload);

        $this->assertResponseStatus(200);
        $this->seeJsonStructure(['access_token', 'expires_at']);
    }
}
