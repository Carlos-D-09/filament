<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    public const DEFAULT_USER_IMAGE = 'users/images/default_user_picture.png';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function booted()
    {
        static::creating(function ($user) {
            // Asigna un valor por defecto a profile_picture si es nulo
            if (is_null($user->profile_picture)) {
                $user->profile_picture = User::DEFAULT_USER_IMAGE;
            }

            //Asignar el id del usuario loggeado
            $user->registered_by ? null : $user->registered_by = Auth::id();
        });

        static::updating(function ($user) {
            if(is_null($user->profile_picture) && $user->getOriginal('profile_picture') != User::DEFAULT_USER_IMAGE){

                //Eliminar la vieja imagen si no era la imagen por defecto y la nueva imagen es nula
                Storage::disk('public')->delete($user->getOriginal('profile_picture'));
                $user->profile_picture = User::DEFAULT_USER_IMAGE;
            }

            if(is_null($user->profile_picture) && $user->getOriginal('profile_picture') == User::DEFAULT_USER_IMAGE){
                //Asignar la ruta de la imagen por defecto para evitar registros vacíos
                $user->profile_picture = User::DEFAULT_USER_IMAGE;
            }

            if($user->profile_picture != $user->getOriginal('profile_picture') && $user->getOriginal('profile_picture') != User::DEFAULT_USER_IMAGE){
                //Eliminar la imagen si ha cambiado y no la vieja imagen no es la original
                Storage::disk('public')->delete($user->getOriginal('profile_picture'));
            }
        });

        static::deleting(function ($user){
            Storage::disk('public')->delete($user->profile_picture);
        });
    }

    //Relationships
    public function role(): BelongsTo{
        return $this->belongsTo(Role::class);
    }

    public function registeredBy(): BelongsTo{
        return $this->belongsTo(User::class,'registered_by');
    }

    public function ownedGroups(): HasMany{
        return $this->hasMany(Group::class);
    }

    public function groups(): BelongsToMany{
        return $this->belongsToMany(Group::class);
    }

    public function tasks(): HasMany{
        return $this->hasMany(Task::class,'created_by');
    }

    public function assignments(): HasMany{
        return $this->hasMany(Task::class, 'assigned_to');
    }

}
