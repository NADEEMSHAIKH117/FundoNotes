<?php

namespace App\Http\Controllers;

use App\Exceptions\FundoNoteException;
use App\Models\LabelNotes;
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
    public static $colours  =  array(
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
        'teal' => 'rgb(0,128,128)',
        'white' => 'rgb(255,255,255)',
    );
    /**
     * @OA\Post(
     *   path="/api/auth/createNotes",
     *   summary="create note",
     *   description="create user note",
     *   @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"title","description"},
     *               @OA\Property(property="title", type="string"),
     *               @OA\Property(property="description", type="string"),
     *               @OA\Property(property="pin", type="string"),
     *               @OA\Property(property="archive", type="string"),
     *               @OA\Property(property="colour", type="string"),
     *                  
     *            ),
     *        ),
     *    ),
     *   @OA\Response(response=201, description="notes created successfully"),
     *   @OA\Response(response=401, description="Invalid authorization token"),
     *   security={
     *       {"Bearer": {}}
     *     }
     * )
     * This function takes User access token and checks if it is
     * authorised or not if so and it procees for the note creation
     * and created it successfully.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function createNotes(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|between:2,50',
            'description' => 'required|string|between:3,1000',

        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        if (($request->has('pin')) == null) {
            $pin = 0;
        } else {
            $pin = $request->input('pin');
        }
        if (($request->has('archive')) == null) {
            $archive = 0;
        } else {
            $archive = $request->input('archive');
        }
        if (($request->has('colour')) == null) {
            $colour = 'rgb(255,255,255)';
        } else {
            $colour = $request->input('colour');
        }

        try {
            $note = new Notes;
            $note->title = $request->input('title');
            $note->description = $request->input('description');
            $note->pin = $pin;
            $note->archive = $archive;
            $note->colour = $colour;

            $colour_name = strtolower($request->colour);
            if (isset(NotesController::$colours[$colour_name])) {
                $note->colour = NotesController::$colours[$colour_name];
            }

            $note->user_id = Auth::user()->id;
            $note->save();
            if (!$note) {
                throw new FundoNoteException("Invalid Authorization token ", 401);
            }
            Cache::remember('notes', 3600, function () {
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

    /**
     *   @OA\Get(
     *   path="/api/auth/displayNoteById",
     *   summary="display Note",
     *   description="user display Note",
     *   @OA\RequestBody(
     *    ),
     *   @OA\Response(response=200, description="All Notes are Fetched Successfully"),
     *   @OA\Response(response=401, description="Invalid authorization token"),
     *   security={
     *       {"Bearer": {}}
     *     }
     * )
     * This function takes access token and note id and finds
     * if there is any note existing on that User id and note id if so
     * it successfully returns that note id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function displayNoteById()
    {

        try {
            $user = Auth::user();
            if (!$user) {
                Log::error('Invalid User');
                throw new FundoNoteException("Invalid authorization token", 401);
            }
            Cache::remember('notes', 3600, function () {
                return DB::table('notes')->get();
            });

            $notes = new Notes();
            return response()->json([
                'status' => 201,
                'message' => 'Fetched Notes Successfully',
                'Note' => $notes->getAllNotes($user)
            ], 201);

            if (!$notes) {
                throw new FundoNoteException("Notes not found", 404);
            }
        } catch (FundoNoteException $exception) {
            return $exception->message();
        }
    }
    /**
     *   @OA\Post(
     *   path="/api/auth/updateNoteById",
     *   summary="update note",
     *   description="update user note",
     *   @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="application/x-www-form-urlencoded",
     *            @OA\Schema(
     *               type="object",
     *               required={"id","title","description"},
     *               @OA\Property(property="id"),
     *               @OA\Property(property="title", type="string"),
     *               @OA\Property(property="description", type="string"),
     *            ),
     *        ),
     *    ),
     *   @OA\Response(response=200, description="Note successfully updated"),
     *   @OA\Response(response=404, description="Notes not found"),
     *   @OA\Response(response=401, description="Invalid authorization token"),
     *   security={
     *       {"Bearer": {}}
     *     }
     * )
     * This function takes the User access token and note id which
     * user wants to update and finds the note id if it is existed
     * or not if so, updates it successfully.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateNoteById(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'title' => 'string|between:2,30',
            'description' => 'string|between:3,1000',
            'pin' => 'int|between:0,1',
            'archive' => 'int|between:0,1',
            'colour' => 'string|max:20',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 401);
        }
        try {
            $id = $request->input('id');
            $currentUser = JWTAuth::parseToken()->authenticate();
            $note = $currentUser->notes()->find($id);
            Cache::remember('notes', 3600, function () {
                return DB::table('notes')->get();
            });

            if (!$note) {
                Log::error('Notes Not Found', ['id' => $request->id]);
                return response()->json(['message' => 'Notes not Found'], 404);
            }

            $note->fill($request->all());

            $colour_name = strtolower($request->colour);
            if (isset(NotesController::$colours[$colour_name])) {
                $note->colour = NotesController::$colours[$colour_name];
            }
            
            $note->user_id = Auth::user()->id;

            if ($note->save()) {
                Log::info('notes updated', ['user_id' => $currentUser, 'note_id' => $request->id]);
                return response()->json(['message' => 'Note updated Successfully'], 200);
            }
            if (!($note->save())) {
                throw new FundoNoteException("Invalid Authorization token ", 404);
            }
        } catch (FundoNoteException $e) {
            return response()->json(['message' => $e->message(), 'status' => $e->statusCode()]);
        }
    }

    /**
     *   @OA\post(
     *   path="/api/auth/deleteNoteById",
     *   summary="delete note",
     *   description="delete user note",
     *   @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="application/x-www-form-urlencoded",
     *            @OA\Schema(
     *               type="object",
     *               required={"id"},
     *               @OA\Property(property="id", type="integer"),
     *            ),
     *        ),
     *    ),
     *   @OA\Response(response=200, description="Note deleted Successfully"),
     *   @OA\Response(response=404, description="Notes not found"),
     *   @OA\Response(response=401, description="Invalid Authorization token"),
     *   security={
     *       {"Bearer": {}}
     *     }
     * )
     * This function takes the User access token and note id which
     * user wants to delete and finds the note id if it is existed
     * or not if so, deletes it successfully.
     *
     * @return \Illuminate\Http\JsonResponse
     */
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
            Cache::remember('notes', 3600, function () {
                return DB::table('notes')->get();
            });

            if (!$note) {
                Log::error('Notes Not Found', ['id' => $request->id]);
                return response()->json(['message' => 'Notes not Found'], 404);
            }

            if ($note->delete()) {
                Log::info('notes deleted', ['user_id' => $currentUser, 'note_id' => $request->id]);
                return response()->json(['message' => 'Note deleted Successfully'], 200);
            }
            if (!($note->delete())) {
                throw new FundoNoteException("Invalid Authorization token ", 401);
            }
        } catch (FundoNoteException $e) {
            return response()->json(['message' => $e->message(), 'status' => $e->statusCode()]);
        }
    }

    /**
     * This function takes the User access token and checks if it 
     * authorised or not and it takes the note_id and pins  it 
     * successfully if notes is exist.  
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * @OA\Post(
     *   path="/api/auth/pinNoteById",
     *   summary="Pin Note",
     *   description=" Pin Note ",
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
     *   @OA\Response(response=201, description="Note Pinned Sucessfully"),
     *   @OA\Response(response=404, description="Notes not Found"),
     *   security = {
     * {
     * "Bearer" : {}}}
     * )
     */
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
            if ($note->archive == 1) {
                $note->archive = 0;
                $note->save();
            }
            $note->pin = 1;
            $note->save();

            Log::info('notes pinned', ['user_id' => $currentUser, 'note_id' => $request->id]);
            return response()->json(['message' => 'Note pinned Successfully'], 201);
        }
    }


    /**
     * This function takes the User access token and checks if it 
     * authorised or not if so, it returns all the pinned notes 
     * successfully.  
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * @OA\Get(
     *   path="/api/auth/getAllPinnedNotes",
     *   summary="Display All Pinned Notes",
     *   description=" Display All Pinned Notes ",
     *   @OA\RequestBody(
     *         
     *    ),
     *   @OA\Response(response=404, description="Invalid token"),
     *   @OA\Response(response=201, description="Fetched Pinned Notes Successfully"),
     *   security = {
     * {
     * "Bearer" : {}}}
     * )
     */
    public function getAllPinnedNotes()
    {
        try {
            $notes = new Notes();
            $notes->user_id = auth()->id();
            $currentUser = JWTAuth::parseToken()->authenticate();
            if (!$currentUser) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Invalid authorization token'
                ], 404);
            }

            if ($notes->user_id == auth()->id()) {
                $usernotes = new Notes();
                return response()->json(
                    [
                        'message' => 'Fetched Pinned Notes Successfully',
                        $usernotes->getAllPinNotes($currentUser)
                    ],
                    201
                );

                if ($usernotes == '[]') {
                    return response()->json(['message' => 'Notes not found'], 404);
                }
            } else {
                throw new FundoNoteException("Invalid token", 404);
            }
        } catch (FundoNoteException $exception) {
            return $exception->message();
        }
    }


    /**
     * This function takes the User access token and checks if it 
     * authorised or not and it takes the note_id and Archives it 
     * successfully if notes exist.  
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * @OA\Post(
     *   path="/api/auth/archiveNoteById",
     *   summary="Archive Note",
     *   description=" Archive Note ",
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
     *   @OA\Response(response=201, description="Note Archived Sucessfully"),
     *   @OA\Response(response=404, description="Notes not Found"),
     *   security = {
     * {
     * "Bearer" : {}}}
     * )
     */
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
            if ($note->pin == 1) {
                $note->pin = 0;
                $note->save();
            }
            $note->archive = 1;
            $note->save();

            Log::info('notes archived', ['user_id' => $currentUser, 'note_id' => $request->id]);
            return response()->json(['message' => 'Note archived Successfully'], 201);
        }
    }

    /**
     * This function takes the User access token and checks if it 
     * authorised or not if so, it returns all the Archived notes 
     * successfully.  
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * @OA\Get(
     *   path="/api/auth/getAllArchiveNotes",
     *   summary="Display All Archived Notes",
     *   description=" Display All Archived Notes ",
     *   @OA\RequestBody(
     *         
     *    ),
     *   @OA\Response(response=404, description="Invalid token"),
     *   @OA\Response(response=201, description="Fetched Archived Notes"),
     *   @OA\Response(response=403, description="Notes not found"),
     *   security = {
     * {
     * "Bearer" : {}}}
     * )
     */
    public function getAllArchiveNotes()
    {
        try {
            $notes = new Notes();
            $notes->user_id = auth()->id();
            $currentUser = JWTAuth::parseToken()->authenticate();

            if ($notes->user_id == auth()->id()) {
                $usernotes = new Notes();
                return response()->json(
                    [
                        'message' => 'Fetched Archive Notes Successfully',
                        $usernotes->getAllArchive($currentUser)
                    ],
                    201
                );

                if ($usernotes == '[]') {
                    return response()->json(['message' => 'Notes not found'], 403);
                }
            } else {
                throw new FundoNoteException("Invalid token", 404);
            }
        } catch (FundoNoteException $exception) {
            return $exception->message();
        }
    }

    /**
     * This function takes the User access token and checks if it 
     * authorised or not and it takes the note_id and colours it 
     * successfully if notes exist.  
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * @OA\Post(
     *   path="/api/auth/colourNoteById",
     *   summary="Colour Note",
     *   description=" Colour Note ",
     *   @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"id" , "colour"},
     *               @OA\Property(property="id", type="integer"),
     *               @OA\Property(property="colour", type="string"),
     *            ),
     *        ),
     *    ),
     *   @OA\Response(response=201, description="Note coloured Sucessfully"),
     *   @OA\Response(response=404, description="Notes not Found"),
     *  @OA\Response(response=400, description="Colour Not Specified in the List"),
     *   security = {
     * {
     * "Bearer" : {}}}
     * )
     */
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

    /**
     * This function takes the User access token and search key to search
     * if the access token is valid it returns all the notes which has given
     * search key for that particular user.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * @OA\Post(
     *   path="/api/auth/searchAllNotes",
     *   summary="Search Note",
     *   description=" Search Note ",
     *   @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"search"},
     *               @OA\Property(property="search", type="string"),
     *            ),
     *        ),
     *    ),
     *   @OA\Response(response=201, description="Note Fetched Sucessfully"),
     *   @OA\Response(response=404, description="Notes not Found"),
     *   security = {
     * {
     * "Bearer" : {}}}
     * )
     */
    public function searchAllNotes(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $searchKey = $request->input('search');
        $currentUser = JWTAuth::parseToken()->authenticate();

        if ($currentUser) {
            $usernotes = new Notes();
            return response()->json(
                [
                    'message' => 'Fetched Notes Successfully',
                    'notes' => $usernotes->search($currentUser, $searchKey)
                ],
                201
            );

            if ($usernotes == '[]') {
                return response()->json(['message' => 'No results'], 404);
            }
        }
        return response()->json(['message' => 'Invalid authorization token'], 403);
    }
}
