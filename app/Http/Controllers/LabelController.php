<?php

namespace App\Http\Controllers;

use App\Models\Label;
use App\Models\LabelNotes;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Contracts\Service\Attribute\Required;
use Illuminate\Support\Facades\Cache;
use Tymon\JWTAuth\Facades\JWTAuth;

class LabelController extends Controller
{
    public function createLabel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'labelname' => 'required|string|between:2,15',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $currentUser = JWTAuth::parseToken()->authenticate();
        if ($currentUser) {
            $labelName = Label::where('labelname', $request->lablename)->first();
            if ($labelName) {
                Log::alert('Label Created : ', ['email' => $request->email]);
                return response()->json(['message' => 'Label Name already exists'], 401);
            }

            $label = new Label;
            $label->labelname = $request->get('labelname');

            if ($currentUser->labels()->save($label)) {
                return response()->json(['message' => 'Label added Sucessfully'], 201);
            }
            return response()->json(['message' => 'Could not add label'], 405);
        }
        return response()->json(['message' => 'Invalid authorization token'], 404);
    }

    public function displayLabelById()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $labels = Label::where('user_id', '=', $user->id)->get();
        if ($labels == '') {
            return response()->json(['message' => 'Label not Found'], 404);
        }
        return response()->json([
            'message' => 'All Labels are Fetched Successfully',
            'label' => $labels
        ]);
    }

    public function updateLabelById(Request $request)
    { {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'labelname' => 'required|string|between:2,15',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                return response()->json([
                    'status' => 401,
                    'message' => 'Invalid authorization token'
                ], 401);
            }

            $notes = Label::where('id', $request->id)->first();
            if (!$notes) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Label not Found'
                ], 404);
            }

            $notes->update([
                'id' => $request->id,
                'labelname' => $request->labelname,
            ]);

            Cache::forget('labels');
            Cache::forget('notes');
            return response()->json([
                'status' => 200,
                'message' => "Label updated Sucessfully"
            ], 200);
        }
    }


    public function deleteLabelById(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        try {
            $id = $request->input('id');
            $currentUser = JWTAuth::parseToken()->authenticate();
            $label = $currentUser->labels()->find($id);

            if (!$label) {
                Log::error('Label Not Found', ['label_id' => $request->id]);
                return response()->json(['message' => 'label not Found'], 404);
            }

            if ($label->delete()) {
                Log::info('label deleted', ['user_id' => $currentUser, 'label_id' => $request->id]);
                return response()->json(['message' => 'Label deleted Successfully'], 201);
            }
        } catch (Exception $e) {
            return response()->json(['message' => 'Invalid Authorization token'], 404);
        }
    }

    public function addLabelByNoteId(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'label_id' => 'required',
            'note_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = JWTAuth::parseToken()->authenticate();

        if ($user) {
            $labelnote = LabelNotes::where('note_id', $request->note_id)->where('label_id', $request->label_id)->first();
            if ($labelnote) {
                return response()->json([
                    'status' => 409,
                    'message' => 'Note Already have a label'
                ], 409);
            }

            $labelnotes = new LabelNotes();
            $labelnotes->label_id = $request->label_id;
            $labelnotes->note_id = $request->note_id;
            if ($user->label_notes()->save($labelnotes)) {
                Cache::forget('notes');
                return response()->json([
                    'status' => 201,
                    'message' => 'Label notes added Successfully',
                ], 201);
            }
        }
        return response()->json([
            'status' => 401,
            'message' => 'Invalid authorization token'
        ], 401);
    }

    public function deleteNoteLabel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'label_id' => 'required',
            'note_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = JWTAuth::parseToken()->authenticate();

        if ($user) {
            $labelnote = LabelNotes::where('label_id', $request->label_id)->where('note_id', $request->note_id)->first();
            if (!$labelnote) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Note not found with this label'
                ], 404);
            }

            $labelnote->delete($labelnote->id);
            Cache::forget('notes');
            return response()->json([
                'status' => 201,
                'message' => 'Label successfully deleted'
            ], 200);
        }

        return response()->json([
            'status' => 401,
            'message' => 'Invalid authorization token'
        ], 401);
    }
}
