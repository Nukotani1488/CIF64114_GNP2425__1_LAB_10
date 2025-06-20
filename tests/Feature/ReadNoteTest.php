<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Note;

class ReadNoteTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_guest_can_read_notes()
    {
        $note = Note::factory()->create();

        $response = $this->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee($note->title)
                 ->assertSee($note->content);
    }

    public function test_user_can_read_notes()
    {
        $note = Note::factory()->create();
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee($note->title)
                 ->assertSee($note->content);
    }
}
