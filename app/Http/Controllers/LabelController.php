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

    /**
     * This function takes the User access token and labelname
     * creates a label for that respective user.
     */
    /**
     * @OA\Post(
     *   path="/api/auth/createLabel",
     *   summary="Create Label",
     *   description=" Create Label ",
     *   @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"labelname"},
     *               @OA\Property(property="labelname", type="string"),
     *            ),
     *        ),
     *    ),
     *   @OA\Response(response=201, description="Label added Sucessfully"),
     *   @OA\Response(response=404, description="Invalid authorization token"),
     *   @OA\Response(response=401, description="Label Name already exists"),
     *   @OA\Response(response=405, description="Could not add label"),
     *   security = {
     * {
     * "Bearer" : {}}}
     * )
     */
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

    /**
     * This function takes the User access token and label id and 
     * displays that respective label id.
     * 
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * @OA\Get(
     *   path="/api/auth/displayLabelById",
     *   summary="Display Label",
     *   description=" Display Label ",
     *   @OA\RequestBody(
     *         
     *    ),
     *   @OA\Response(response=404, description="Label not Found"),
     *   @OA\Response(response=200, description="All Labels are Fetched Successfully"),
     *   security = {
     * {
     * "Bearer" : {}}}
     * )
     */
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
        ], 200);
    }

    /**
     * This function takes the User access token and label id and 
     * updates the label for the respective id.
     * 
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * @OA\Post(
     *   path="/api/auth/updateLabelById",
     *   summary="Update Label",
     *   description=" Update label ",
     *   @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"id" , "labelname"},
     *               @OA\Property(property="id", type="integer"),
     *               @OA\Property(property="labelname", type="string"),
     *            ),
     *        ),
     *    ),
     *   @OA\Response(response=200, description="Label updated Sucessfully"),
     *   @OA\Response(response=401, description="Invalid authorization token"),
     *   @OA\Response(response=404, description="Label not Found"),
     *   security = {
     * {
     * "Bearer" : {}}}
     * )
     */
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

    /**
     * This function takes the User access token and label id and 
     * and deleted that particular label id.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * @OA\Post(
     *   path="/api/auth/deleteLabelById",
     *   summary="Delete Label",
     *   description=" Delete label ",
     *   @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"id"},
     *               @OA\Property(property="id", type="integer"),
     *            ),
     *        ),
     *    ),
     *   @OA\Response(response=201, description="Label deleted Sucessfully"),
     *   @OA\Response(response=404, description="Invalid authorization token"),
     *   @OA\Response(response=403, description="label not Found"),
     *   security = {
     * {
     * "Bearer" : {}}}
     * )
     */
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
                return response()->json(['message' => 'label not Found'], 403);
            }

            if ($label->delete()) {
                Log::info('label deleted', ['user_id' => $currentUser, 'label_id' => $request->id]);
                return response()->json(['message' => 'Label deleted Successfully'], 201);
            }
        } catch (Exception $e) {
            return response()->json(['message' => 'Invalid Authorization token'], 404);
        }
    }

    /**
     * This function takes the User access token and note id and 
     * creates a label for that respective note is and user.
     * 
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * @OA\Post(
     *   path="/api/auth/addLabelByNoteId",
     *   summary="Add Label By Note Id",
     *   description=" Add Label By Note Id ",
     *   @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"label_id" , "note_id"},
     *               @OA\Property(property="label_id", type="integer"),
     *               @OA\Property(property="note_id", type="integer"),
     *            ),
     *        ),
     *    ),
     *   @OA\Response(response=201, description="Label notes added Successfully"),
     *   @OA\Response(response=401, description="Invalid authorization token"),
     *   @OA\Response(response=409, description="Note Already have a label"),
     *   security = {
     * {
     * "Bearer" : {}}}
     * )
     */
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
                    // 'labelnote' => $labelnotes
                ], 201);
            }
        }
        return response()->json([
            'status' => 401,
            'message' => 'Invalid authorization token'
        ], 401);
    }

    /**
     *   @OA\POST(
     *   path="/api/auth/deleteNoteLabel",
     *   summary="delete note label",
     *   description="delete note label",
     *   @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="application/x-www-form-urlencoded",
     *            @OA\Schema(
     *               type="object",
     *               required={"label_id","note_id"},
     *               @OA\Property(property="label_id", type="integer"),
     *               @OA\Property(property="note_id", type="integer"),
     *            ),
     *        ),
     *    ),
     *   @OA\Response(response=201, description="Label successfully deleted"),
     *   @OA\Response(response=404, description="Note not found with this label"),
     *   @OA\Response(response=401, description="Invalid authorization token"),
     *   security={
     *       {"Bearer": {}}
     *     }
     * )
     * function to delete the label from the note
     *
     * @var req Request
     */
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

    /**
     * This function takes the User access token and label id and note id and 
     * displays that respective label id.
     * 
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * @OA\Get(
     *   path="/api/auth/displayNoteLabel",
     *   summary="Display Label note",
     *   description=" Display LabelNote ",
     *   @OA\RequestBody(
     *         
     *    ),
     *   @OA\Response(response=404, description="Label not Found"),
     *   @OA\Response(response=200, description="All Labels are Fetched Successfully"),
     *   security = {
     * {
     * "Bearer" : {}}}
     * )
     */
    public function displayNoteLabel()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $labelnote = LabelNotes::where('user_id', '=', $user->id)
            ->where('note_id', '=', $user->id)
            ->where('label_id', '=', $user->id)
            ->get();
        if ($labelnote == '') {
            return response()->json(['message' => 'Label not Found'], 404);
        }
        return response()->json([
            'message' => 'All Labels are Fetched Successfully',
            'labelnote' => $labelnote
        ]);
    }
}
