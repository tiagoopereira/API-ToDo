<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Todo extends Model
{
    use UsesUuid, HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'id',
        'title',
        'description',
        'done',
        'done_at',
        'user_id',
    ];

    protected $appends = ['links'];

    protected $casts = ['done' => 'boolean'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getLinksAttribute(): array
    {
        return [
            'self' => "/api/todos/{$this->id}",
        ];
    }
}
