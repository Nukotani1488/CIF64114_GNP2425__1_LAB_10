<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Note;

class UpdateNoteTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
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
    
    public function test_guest_cannot_update_note()
    {
        $note = Note::factory()->create();

        $response = $this->put(route('notes.update', ['note' => $note->id]), [
            'content' => 'Guest trying to update note.',
        ]);

        $response->assertRedirect('/login');
        $note->refresh();
        $this->assertNotEquals('Guest trying to update note.', $note->content);
    }
}
