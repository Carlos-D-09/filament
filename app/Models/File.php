<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class File extends Model
{
    //Relationships
    public function task(): BelongsTo {
        return $this->belongsTo(Task::class);
    }
}
