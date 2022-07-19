<?php

use App\Models\User;
use App\Repository\UsersRepository;
use App\Service\AuthService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\PersonalAccessTokenResult;
use Laravel\Passport\Token;
use PHPUnit\Framework\MockObject\MockObject;

class AuthServiceTest extends TestCase
{
    private string $email = 'teste@email.com';

    private string $password = 'teste';

    private MockObject $user;

    private MockObject $usersRepository;

    private AuthService $authService;

    private PersonalAccessTokenResult $token;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = $this->createMock(User::class);
        $this->usersRepository = $this->createMock(UsersRepository::class);
        $this->authService = new AuthService($this->usersRepository);
        $token = $this->createMock(Token::class);
        $this->token = new PersonalAccessTokenResult(md5('token'), $token);
    }

    public function testNonExistentUserThrowsAnException(): void
    {
        $this->expectException(AuthenticationException::class);
        $this->expectExceptionMessage('Wrong credentials.');

        $this->usersRepository->method('findOneBy')
            ->with('email', $this->email)
            ->willReturn(null);

        $this->authService->login($this->email, $this->password);
    }

    public function testWrongPasswordThrowsAnException(): void
    {
        $this->expectException(AuthenticationException::class);
        $this->expectExceptionMessage('Wrong credentials.');

        $this->usersRepository->method('findOneBy')
            ->with('email', $this->email)
            ->willReturn($this->user);

        Hash::shouldReceive('check')
            ->with($this->password, $this->user->password)
            ->andReturn(false);

        $this->authService->login($this->email, $this->password);
    }

    public function testUserCanLoginWithTheCorrectCredentials(): void
    {
        $expected = [
            'access_token' => md5('token'),
            'expires_at' => null,
        ];

        $this->usersRepository->method('findOneBy')
            ->with('email', $this->email)
            ->willReturn($this->user);

        Hash::shouldReceive('check')
            ->with($this->password, $this->user->password)
            ->andReturn(true);

        $this->user->method('createToken')
            ->with('users')
            ->willReturn($this->token);

        $data = $this->authService->login($this->email, $this->password);

        $this->assertIsArray($data);
        $this->assertSame($expected, $data);
    }
}
