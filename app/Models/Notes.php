<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class Notes extends Model
{
    use HasFactory;
    protected $table = 'notes';
    protected $fillable = [
        'title',
        'description',
        'pin',
        'archive',
        'colour',
        'label_id'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function label()
    {
        return $this->hasMany(Label::class);
    }
    public function labelnote()
    {
        return $this->belongsTo(LabelNotes::class);
    }
    public function getAllNotes($user)
    {
        $notes = Notes::leftJoin('collaborators', 'collaborators.note_id', '=', 'notes.id')
            ->leftJoin('label_notes', 'label_notes.note_id', '=', 'notes.id')
            ->leftJoin('labels', 'labels.id', '=', 'label_notes.label_id')
            ->select('notes.id', 'notes.title', 'notes.description', 'notes.pin', 'notes.archive', 'notes.colour', 'collaborators.email as Collaborator', 'labels.labelname')
            ->where('notes.user_id', '=', Auth::user()->id)->orWhere('collaborators.email', '=', $user->email)->paginate(8);
        return $notes;
    }
    public function getAllPinNotes($currentUser)
    {
        $usernotes = Notes::leftJoin('collaborators', 'collaborators.note_id', '=', 'notes.id')
            ->leftJoin('label_notes', 'label_notes.note_id', '=', 'notes.id')
            ->leftJoin('labels', 'labels.id', '=', 'label_notes.label_id')
            ->select('notes.id', 'notes.title', 'notes.description', 'notes.pin', 'notes.archive', 'notes.colour', 'collaborators.email as Collaborator', 'labels.labelname')
            ->where([['notes.user_id', '=', $currentUser->id], ['pin', '=', 1]])->orWhere('collaborators.email', '=', $currentUser->email)->paginate(3);
        return $usernotes;
    }
    public function getAllArchive($currentUser)
    {
        $usernotes = Notes::leftJoin('collaborators', 'collaborators.note_id', '=', 'notes.id')
            ->leftJoin('label_notes', 'label_notes.note_id', '=', 'notes.id')
            ->leftJoin('labels', 'labels.id', '=', 'label_notes.label_id')
            ->select('notes.id', 'notes.title', 'notes.description', 'notes.pin', 'notes.archive', 'notes.colour', 'collaborators.email as Collaborator', 'labels.labelname')
            ->where([['notes.user_id', '=', $currentUser->id], ['archive', '=', 1]])->orWhere('collaborators.email', '=', $currentUser->email)->paginate(3);
        return $usernotes;
    }
    public function search($currentUser, $searchKey)
    {
        $usernotes = Notes::leftJoin('collaborators', 'collaborators.note_id', '=', 'notes.id')
            ->leftJoin('label_notes', 'label_notes.note_id', '=', 'notes.id')
            ->leftJoin('labels', 'labels.id', '=', 'label_notes.label_id')
            ->select('notes.id', 'notes.title', 'notes.description', 'notes.pin', 'notes.archive', 'notes.colour', 'collaborators.email as Collaborator', 'labels.labelname')
            ->where('notes.user_id', '=', $currentUser->id)->Where('notes.title', 'like', '%' . $searchKey . '%')
            ->orWhere('notes.user_id', '=', $currentUser->id)->Where('notes.description', 'like', '%' . $searchKey . '%')
            ->orWhere('notes.user_id', '=', $currentUser->id)->Where('labels.labelname', 'like', '%' . $searchKey . '%')
            ->orWhere('collaborators.email', '=', $currentUser->email)->Where('notes.title', 'like', '%' . $searchKey . '%')
            ->orWhere('collaborators.email', '=', $currentUser->email)->Where('notes.description', 'like', '%' . $searchKey . '%')
            ->orWhere('collaborators.email', '=', $currentUser->email)->Where('labels.labelname', 'like', '%' . $searchKey . '%')
            ->paginate(3);

        return $usernotes;
    }
}
