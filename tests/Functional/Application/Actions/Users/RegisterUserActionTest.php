<?php


namespace Tests\Functional\Application\Actions\Users;

use DI\Container;
use Tests\Functional\Application\AppTestCase;
use App\Domain\Users\UserRepository;
use App\Infrastructure\Users\InMemUserRepository;

class RegisterUserActionTest extends AppTestCase {

    public function testUserCanBeRegistered() {
        $app = $this->getApp();

        /** @var Container $container */
        $container = $app->getContainer();
        $container->set(UserRepository::class, new InMemUserRepository());

        $user = [
            'email' => 'jhon@example.com',
            'first_name' => 'Jhon',
            'last_name' => 'Doe',
        ];

        $userPayload = \json_encode($user);

        $request = $this->createRequest('POST', '/users');
        $request->getBody()->write($userPayload);
        $request->getBody()->rewind();

        $response = $app->handle($request);
        $actualPayload = (string) $response->getBody();
        $res = \json_decode($actualPayload);

        $this->assertIsObject($res);
        $this->assertIsObject($res->data);

        $this->assertEquals(200, $res->statusCode);
        $this->assertTrue($res->data->id > 0);
    }
}