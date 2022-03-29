<?php

use App\Models\User;
use Laravel\Lumen\Testing\DatabaseMigrations;

class UsersControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

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
        $this->seeInDatabase('users', ['email' => $payload['email']]);
        $this->seeJsonContains(['name' => $payload['name']]);
        $this->seeJsonContains(['email' => $payload['email']]);
    }

    public function testUserCanVisualizeYourProfile(): void
    {
        $this->actingAs($this->user)->get(route('users.profile'));

        $this->assertResponseOk();
        $this->seeJsonContains(['name' => $this->user->name]);
        $this->seeJsonContains(['email' => $this->user->email]);
    }

    public function testUserCanUpdateYourProfile(): void
    {
        $payload = [
            'name' => 'testName',
            'email' => 'test@test.com',
            'password' => 'update123'
        ];

        $this->actingAs($this->user)->put(route('users.update'), $payload);

        $this->assertResponseOk();
        $this->seeInDatabase('users', ['email' => $payload['email']]);
        $this->seeJsonContains(['name' => $payload['name']]);
        $this->seeJsonContains(['email' => $payload['email']]);
    }

    public function testUserCanDeleteYourProfile(): void
    {
        $this->actingAs($this->user)->delete(route('users.delete'));

        $this->assertResponseStatus(204);
        $this->notSeeInDatabase('users', ['id' => $this->user->id]);
    }
}
