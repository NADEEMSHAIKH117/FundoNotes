<?php
namespace App\Models;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'password',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // //Mutator

    public function setFirstnameAttribute($value) {
        $this->attributes['firstname'] = ucfirst($value);
    }

    public function setLastnameAttribute($value) {
         $this->attributes['lastname'] = ucfirst($value);
    }

    //Accessors
    
    public function getFirstnameAttribute($value) {
        return 'Mr/s '.ucfirst($value);
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier() {
        return $this->getKey();
    }
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims() {
        return [];
    }  
    public function notes()
    {
        return $this->hasMany('App\Models\Notes');
    }
    public function labels()
    {
        return $this->hasMany('App\Models\Label');
    }
    public function label_notes()
    {
        return $this->hasMany('App\Models\LabelNotes');
    }
    public function collaborators()
    {
        return $this->hasMany('App\Models\Collaborator');
    }
}
