<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Event; // Importe o seu model

class ExampleTest extends TestCase
{
    use RefreshDatabase; // Garante um banco de dados limpo para cada teste

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        // 1. Crie os dados necessários para a página funcionar
        Event::factory()->create();

        // 2. Agora acesse a página
        $response = $this->get('/');

        // 3. Verifique se a resposta foi bem-sucedida
        $response->assertStatus(200);
    }
}