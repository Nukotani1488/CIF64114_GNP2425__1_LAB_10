<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Note;

class NoteController extends Controller
{
    function showNotes()
    {
        $notes = Note::all();
        return view('dashboard', ['notes' => $notes]);
    }

    function createNote(Request $request)
    {
        $note = new Note();
        $note->uid = auth()->id();
        $note->title = $request->input('title');
        $note->content = $request->input('content');
        $note->save();

        return redirect()->route('dashboard');
    }

    function updateContent(Request $request, Note $note)
    {
        $user = auth()->user();
        if ($note->uid !== $user->id) {
            return redirect()->route('dashboard')->withErrors('You do not have permission to edit this note.');
        }
        $note->updateContent($request->input('content'));

        return redirect()->route('dashboard');
    }

    function deleteNote(Note $note)
    {
        $user = auth()->user();
        if ($note->uid !== $user->id) {
            return redirect()->route('dashboard')->withErrors('You do not have permission to delete this note.');
        }
        $note->delete();

        return redirect()->route('dashboard');
    }
}
