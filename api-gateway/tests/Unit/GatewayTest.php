<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Laravel\Passport\Passport;
use Laravel\Passport\Client;

class GatewayTest extends TestCase
{

    public function testCanNotGetMicroserviceBWithoutToken()
    {
        $response = $this->get('/api/microservice-b');
        $response->assertStatus(401);
    }

    public function testCanNotGetMicroserviceBWithoutScope()
    {
        Passport::actingAsClient(
            Client::factory()->create(),
            ['access-microservice-c']
        );

        $response = $this->get('/api/microservice-b');
        $response->assertStatus(403);
    }

    public function testCanGetMicroserviceBWithTokenAndScope()
    {
        Passport::actingAsClient(
            Client::factory()->create(),
            ['access-microservice-b']
        );

        $response = $this->get('/api/microservice-b');
        $response->assertStatus(200);
    }
}
