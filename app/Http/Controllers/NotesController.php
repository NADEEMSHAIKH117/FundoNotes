<?php

namespace App\Http\Controllers;

use App\Models\Notes;
use Illuminate\Http\Request;

class NotesController extends Controller
{
    public function createNotes(Request $request)
    {   
        $request->validate([
            'title'=>'required|max:191',
            'description'=>'required|max:191'
        ]);

        $note = new Notes; //calling model
        $note->title=$request->title;
        $note->description=$request->description;
        $note->save();
        return response()->json(['message'=>'Notes added Successfully'],200);
    }

    public function displayNotes()
    {
        $note = Notes::all();
        return response()->json(['Notes'=>$note],200);

    }
}
