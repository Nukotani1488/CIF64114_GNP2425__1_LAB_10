<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Note;

class NoteTest extends TestCase
{
    use RefreshDatabase;

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

    public function test_user_can_update_own_note()
    {
        $user = User::factory()->create();
        $note = Note::factory()->create(['uid' => $user->id]);
        $this->actingAs($user);

        $response = $this->put(route('notes.update', ['note' => $note->id]), [
            'content' => 'Updated content for the note.',
        ]);

        $response->assertRedirect('/dashboard');
        $note->refresh();
        $this->assertEquals('Updated content for the note.', $note->content);
    }

    public function test_user_cannot_update_others_note()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $note = Note::factory()->create(['uid' => $otherUser->id]);
        $this->actingAs($user);

        $response = $this->put(route('notes.update', ['note' => $note->id]), [
            'content' => 'Trying to update another user\'s note.',
        ]);

        $response->assertRedirect('/dashboard');
        $note->refresh();
        $this->assertNotEquals('Trying to update another user\'s note.', $note->content);
    }

    public function test_user_can_delete_own_note()
    {
        $user = User::factory()->create();
        $note = Note::factory()->create(['uid' => $user->id]);
        $this->actingAs($user);

        $response = $this->delete(route('notes.delete', ['note' => $note->id]));

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseMissing('notes', ['id' => $note->id]);
    }

    public function test_user_cannot_delete_others_note()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $note = Note::factory()->create(['uid' => $otherUser->id]);
        $this->actingAs($user);

        $response = $this->delete(route('notes.delete', ['note' => $note->id]));

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('notes', ['id' => $note->id]);
    }
}
