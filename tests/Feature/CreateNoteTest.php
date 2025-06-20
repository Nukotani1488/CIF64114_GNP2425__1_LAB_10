<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Note;


class CreateNoteTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_guest_cannot_create_note()
    {
        $response = $this->post(route('notes.create'), [
            'title' => 'Test Note',
            'content' => 'This is a test note content.',
        ]);

        $response->assertRedirect('/login');
        $this->assertDatabaseMissing('notes', [
            'title' => 'Test Note',
            'content' => 'This is a test note content.',
        ]);
    }

    public function test_user_can_create_note()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('notes.create'), [
            'title' => 'Test Note',
            'content' => 'This is a test note content.',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('notes', [
            'title' => 'Test Note',
            'content' => 'This is a test note content.',
            'uid' => $user->id,
        ]);
    }

}
