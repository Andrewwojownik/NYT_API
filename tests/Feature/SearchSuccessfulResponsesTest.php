<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class SearchSuccessfulResponsesTest extends TestCase
{
    public function test_simple()
    {
        Http::fake([
            Config::get('nyt_api.url') . '/*' => Http::response(
                file_get_contents(base_path() . '/tests/example_response.json'),
                200,
                []
            ),
        ]);

        $response = $this->post('/api/search');

        $response->assertStatus(200);
    }

    public function test_title()
    {
        Http::fake([
            Config::get('nyt_api.url') . '/*' => Http::response(
                file_get_contents(base_path() . '/tests/example_search_response.json'),
                200,
                []
            ),
        ]);

        $response = $this->post('/api/search', ['title' => 'I GIVE YOU']);

        $response->assertStatus(200);
    }

    public function test_author()
    {
        Http::fake([
            Config::get('nyt_api.url') . '/*' => Http::response(
                file_get_contents(base_path() . '/tests/example_search_response.json'),
                200,
                []
            ),
        ]);

        $response = $this->post('/api/search', ['author' => 'Diana Gabaldon']);

        $response->assertStatus(200);
    }

    public function test_isbn10()
    {
        Http::fake([
            Config::get('nyt_api.url') . '/*' => Http::response(
                file_get_contents(base_path() . '/tests/example_search_response.json'),
                200,
                []
            ),
        ]);

        $response = $this->post('/api/search', ['isbn' => '0399178570']);

        $response->assertStatus(200);
    }

    public function test_isbn13()
    {
        Http::fake([
            Config::get('nyt_api.url') . '/*' => Http::response(
                file_get_contents(base_path() . '/tests/example_search_response.json'),
                200,
                []
            ),
        ]);

        $response = $this->post('/api/search', ['isbn' => '9780399178573']);

        $response->assertStatus(200);
    }

    public function test_all_happy_path()
    {
        Http::fake([
            Config::get('nyt_api.url') . '/*' => Http::response(
                file_get_contents(base_path() . '/tests/example_search_response.json'),
                200,
                []
            ),
        ]);

        $response = $this->post('/api/search', [
            'title' => 'I GIVE YOU',
            'author' => 'Diana Gabaldon',
            'isbn' => '9780399178573',
        ]);

        $response->assertStatus(200);

        $response
            ->assertJson(
                fn(AssertableJson $json) => $json->where('status', 'ok')
                    ->has('results', fn(AssertableJson $json) => $json->where('status', 'OK')
                        ->has('copyright')
                        ->where('num_results', 1)
                        ->etc()
                    )
            );
    }
}
