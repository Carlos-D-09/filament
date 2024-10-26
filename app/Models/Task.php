<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    //Relationships
    public function group(): BelongsTo{
        return $this->belongsTo(Group::class);
    }

    public function createdBy(): BelongsTo{
        return $this->belongsTo(User::class,'created_by');
    }

    public function assignedTo(): BelongsTo{
        return $this->belongsTo(User::class,'assigned_to');
    }

    public function files(): HasMany{
        return $this->hasMany(File::class);
    }
}
