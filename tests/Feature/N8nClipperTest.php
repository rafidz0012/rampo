<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use App\Models\User;

class N8nClipperTest extends TestCase
{
    public function test_n8n_page_is_accessible()
    {
        $user = new User(['id' => 1, 'name' => 'Test', 'email' => 'test@example.com']);

        $response = $this->actingAs($user)->get(route('n8n.index'));

        $response->assertStatus(200);
        $response->assertSee('N8n Clipper');
    }

    public function test_n8n_form_submission_sends_data()
    {
        Http::fake();

        $user = new User(['id' => 1, 'name' => 'Test', 'email' => 'test@example.com']);

        $response = $this->actingAs($user)->post(route('n8n.send'), [
            'title' => 'Test Title',
            'url' => 'https://example.com',
            'content' => 'Test Content',
            'webhook_url' => 'https://n8n.example.com/webhook/test',
        ]);

        Http::assertSent(function ($request) {
            return $request->url() == 'https://n8n.example.com/webhook/test' &&
                   $request['title'] == 'Test Title' &&
                   $request['url'] == 'https://example.com';
        });

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }

    public function test_n8n_form_submission_fails_without_webhook_url()
    {
        $user = new User(['id' => 1, 'name' => 'Test', 'email' => 'test@example.com']);
        
        $response = $this->actingAs($user)->post(route('n8n.send'), [
           // Missing required fields
        ]);
        
        $response->assertSessionHasErrors(['title', 'url']);
    }
}
