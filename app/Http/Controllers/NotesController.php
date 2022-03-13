<?php

namespace App\Http\Controllers;

use App\Exceptions\FundoNoteException;
use App\Models\Notes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class NotesController extends Controller
{
    public function createNotes(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|between:2,50',
            'description' => 'required|string|between:3,1000',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        try {
            $note = new Notes; //calling model
            $note->title = $request->input('title');
            $note->description = $request->input('description');
            $note->user_id = Auth::user()->id;
            $note->save();
            if (!$note) {
                throw new FundoNoteException("Invalid Authorization token", 404);
            }
            $value = Cache::remember('notes', 3600, function () {
                return DB::table('notes')->get();
            });
        } catch (FundoNoteException $e) {
            Log::error('Invalid User');
            return response()->json([
                'status' => $e->statusCode(),
                'message' => $e->message()
            ]);
        }
        Log::info('notes created', ['user_id' => $note->user_id]);
        return response()->json([
            'status' => 201,
            'message' => 'notes created successfully'
        ]);
    }


    public function displayNoteById()
    {
        $currentUser = JWTAuth::parseToken()->authenticate();
        $value = Cache::remember('notes', 3600, function () {
            return DB::table('notes')->get();
        });
        if ($currentUser) {
            $user = Notes::where('user_id', '=', $currentUser->id)->get();
        }
        if ($user == '[]') {
            return response()->json(['message' => 'Notes not found'], 404);
        }

        return response()->json([
            'message' => 'All Notes are Fetched Successfully',
            'Notes' => $user
        ], 200);
    }


    public function updateNoteById(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'title' => 'required|string|between:2,30',
            'description' => 'required|string|between:3,1000'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        try {
            $id = $request->input('id');
            $currentUser = JWTAuth::parseToken()->authenticate();
            $note = $currentUser->notes()->find($id);
            $value = Cache::remember('notes', 3600, function () {
                return DB::table('notes')->get();
            });

            if (!$note) {
                Log::error('Notes Not Found', ['id' => $request->id]);
                return response()->json(['message' => 'Notes not Found'], 404);
            }
            $note->fill($request->all());

            if ($note->save()) {
                Log::info('notes update', ['user_id' => $currentUser, 'note_id' => $request->id]);
                return response()->json(['Message' => 'Note Updated Successfully'], 201);
            }
            if (!($note->save())) {
                throw new FundoNoteException("Invalid Authorization token ", 404);
            }
        } catch (FundoNoteException $e) {
            return response()->json(['message' => $e->message(), 'status' => $e->statusCode()]);
        }
    }


    public function deleteNoteById(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        try {
            $id = $request->input('id');
            $currentUser = JWTAuth::parseToken()->authenticate();
            $note = $currentUser->notes()->find($id);
            $value = Cache::remember('notes', 3600, function () {
                return DB::table('notes')->get();
            });

            if (!$note) {
                Log::error('Notes Not Found', ['id' => $request->id]);
                return response()->json(['message' => 'Notes not Found'], 404);
            }

            if ($note->delete()) {
                Log::info('notes deleted', ['user_id' => $currentUser, 'note_id' => $request->id]);
                return response()->json(['message' => 'Note deleted Successfully'], 201);
            }
            if (!($note->delete())) {
                throw new FundoNoteException("Invalid Authorization token ", 404);
            }
        } catch (FundoNoteException $e) {
            return response()->json(['message' => $e->message(), 'status' => $e->statusCode()]);
        }
    }

    public function pinNoteById(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $id = $request->input('id');
        $currentUser = JWTAuth::parseToken()->authenticate();
        $note = $currentUser->notes()->find($id);

        if (!$note) {
            Log::error('Notes Not Found', ['user' => $currentUser, 'id' => $request->id]);
            return response()->json(['message' => 'Notes not Found'], 404);
        }

        if ($note->pin == 0) {
            $user = Notes::where('id', $request->id)
                ->update(['pin' => 1]);

            Log::info('notes pinned', ['user_id' => $currentUser, 'note_id' => $request->id]);
            return response()->json(['message' => 'Note pinned Successfully'], 201);
        }
    }

    public function archiveNoteById(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $id = $request->input('id');
        $currentUser = JWTAuth::parseToken()->authenticate();
        $note = $currentUser->notes()->find($id);

        if (!$note) {
            Log::error('Notes Not Found', ['user' => $currentUser, 'id' => $request->id]);
            return response()->json(['message' => 'Notes not Found'], 404);
        }

        if ($note->archive == 0) {
            $user = Notes::where('id', $request->id)
                ->update(['archive' => 1]);

            Log::info('notes archived', ['user_id' => $currentUser, 'note_id' => $request->id]);
            return response()->json(['message' => 'Note archived Successfully'], 201);
        }
    }

    public function colourNoteById(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'colour' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $id = $request->input('id');
        $currentUser = JWTAuth::parseToken()->authenticate();
        $note = $currentUser->notes()->find($id);

        if (!$note) {
            Log::error('Notes Not Found', ['user' => $currentUser, 'id' => $request->id]);
            return response()->json(['message' => 'Notes not Found'], 404);
        }

        $colours  =  array(
            'green' => 'rgb(0,255,0)',
            'red' => 'rgb(255,0,0)',
            'blue' => 'rgb(0,0,255)',
            'yellow' => 'rgb(255,255,0)',
            'grey' => 'rgb(128,128,128)',
            'purple' => 'rgb(128,0,128)',
            'brown' => 'rgb(165,42,42)',
            'orange' => 'rgb(255,165,0)',
            'pink' => 'rgb(255,192,203)',
            'black' => 'rgb(0,0,0)',
            'silver' => 'rgb(192,192,192)',
            'teal' => 'rgb(0,128,128)'
        );

        $colour_name = strtolower($request->colour);

        if (isset($colours[$colour_name])) {
            $user = Notes::where('id', $request->id)
                ->update(['colour' => $colours[$colour_name]]);

            Log::info('notes coloured', ['user_id' => $currentUser, 'note_id' => $request->id]);
            return response()->json(['message' => 'Note coloured Sucessfully'], 201);
        } else {
            return response()->json(['message' => 'Colour Not Specified in the List'], 400);
        }
    }
}
