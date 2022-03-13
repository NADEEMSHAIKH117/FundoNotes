<?php

namespace App\Http\Controllers;

use App\Models\Label;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Contracts\Service\Attribute\Required;
use Tymon\JWTAuth\Facades\JWTAuth;

class LabelController extends Controller
{
    public function createLabel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'labelname' => 'required|string|between:2,15',
            'note_id' => 'required'
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
            $label->note_id = $request->note_id;

            if ($currentUser->labels()->save($label)) {
                return response()->json(['message' => 'Label added Sucessfully'], 201);
            }
            return response()->json(['message' => 'Could not add label'], 405);
        }
        return response()->json(['message' => 'Invalid authorization token'], 404);
    }

    public function addLabelByNoteId(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'note_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $currentUser = JWTAuth::parseToken()->authenticate();

        if ($currentUser) {
            $id = $request->input('id');
            $note_id = $request->input('note_id');

            $label = $currentUser->labels()->find($id);

            if (!$label) {
                return response()->json(['message' => 'Label not Found'], 404);
            }

            $label->note_id = $request->get('note_id');

            if ($currentUser->labels()->save($label)) {
                return response()->json(['message' => 'Label Added to Note Sucessfully'], 201);
            }
            return response()->json(['message' => 'Label Did Not added to Note'], 403);
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
    {
        $validator = Validator::make($request->all(), [
            'id' => 'Required',
            'labelname' => 'required|string|between:2,15',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        try {
            $id = $request->input('id');
            $currentUser = JWTAuth::parseToken()->authenticate();
            $label = $currentUser->lables()->find($id);

            if (!$label) {
                Log::error('Label not Found', ['label_id' => $request->id]);
                return response()->json(['message' => 'Label not Found'], 400);
            }
            $label->fill($request->all());

            if ($label->save()) {
                Log::info('Label update', ['user_id' => $currentUser, 'label_id' => $request->id]);
                return response()->json(['message' => 'Label updated Sucessfully'], 201);
            }
        } catch (Exception $e) {
            return response()->json(['message' => 'Invalid Authorization token'], 404);
        }
        return $label;
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
}
