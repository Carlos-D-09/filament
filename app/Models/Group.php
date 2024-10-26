<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    //Relationships
    public function users(): BelongsToMany{
        return $this->belongsToMany(User::class, 'group_user');
    }

    public function user(): BelongsTo{
        return $this->belongsTo(User::class);
    }

    public function tasks(): HasMany{
        return $this->hasMany(Task::class);
    }
}
