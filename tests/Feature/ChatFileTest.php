<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ChatFileTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_send_file_in_chat()
    {
        Storage::fake('public');

        $sender = User::factory()->create();
        $receiver = User::factory()->create();

        $file = UploadedFile::fake()->image('test_image.jpg');

        $response = $this->actingAs($sender, 'sanctum')
            ->postJson('/api/chat/messages', [
                'receiver_id' => $receiver->id,
                'file' => $file,
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'id',
                    'sender_id',
                    'receiver_id',
                    'file_path',
                    'file_name',
                    'file_type',
                    'file_url',
                ]
            ]);

        $message = Message::first();
        $this->assertEquals($sender->id, $message->sender_id);
        $this->assertEquals($receiver->id, $message->receiver_id);
        $this->assertNotNull($message->file_path);
        $this->assertEquals('test_image.jpg', $message->file_name);
        
        // Verify file exists in storage
        Storage::disk('public')->assertExists($message->file_path);
        
        // Verify file_url is correct
        $this->assertEquals(asset('storage/' . $message->file_path), $message->file_url);
    }

    public function test_can_send_message_with_file()
    {
        Storage::fake('public');

        $sender = User::factory()->create();
        $receiver = User::factory()->create();

        $file = UploadedFile::fake()->create('document.pdf', 100);

        $response = $this->actingAs($sender, 'sanctum')
            ->postJson('/api/chat/messages', [
                'receiver_id' => $receiver->id,
                'message' => 'Here is the file',
                'file' => $file,
            ]);

        $response->assertStatus(201);
        
        $message = Message::first();
        $this->assertEquals('Here is the file', $message->message);
        $this->assertNotNull($message->file_path);
    }
}
