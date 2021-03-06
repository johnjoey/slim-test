<?php

namespace Tests\Functional\Auth;

use SturentsTest\Models\User;
use Tests\BaseTestCase;
use Tests\UseDatabaseTrait;

class LoginTest extends BaseTestCase
{

    use UseDatabaseTrait;

    /** @test */
    public function a_user_can_obtain_a_jwt_token_after_log_in()
    {
        $password = 'secret';
        $user = User::create([
            'username' => 'first_user',
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'email'    => 'user@example.com',
        ]);

        $payload = [
            'user' => ['email' => $user->email, 'password' => $password],
        ];

        $response = $this->request('POST',
            '/api/users/login',
            $payload

        );

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('token', json_decode((string)$response->getBody(), true)['user']);
    }
}
