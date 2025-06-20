<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Note;

class DeleteNoteTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
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

    public function test_guest_cannot_delete_note()
    {
        $note = Note::factory()->create();

        $response = $this->delete(route('notes.delete', ['note' => $note->id]));

        $response->assertRedirect('/login');
        $this->assertDatabaseHas('notes', ['id' => $note->id]);
    }
}
