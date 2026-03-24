<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class CaptionGeneration extends Model
{
    protected $fillable = [
        'workspace_id',
        'user_id',
        'prompt',
        'tone',
        'platform',
        'generated_caption',
        'model',
        'tokens_used',
    ];

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
