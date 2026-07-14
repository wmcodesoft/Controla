<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToClient;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

final class Blocklist extends Model
{
    use BelongsToClient;

    protected $table = 'blocklist';

    protected $fillable = [
        'client_id',
        'reason',
        'blockable_type',
        'blockable_id',
        'blocked_by',
        'blocked_at',
        'expires_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'blocked_at' => 'datetime',
            'expires_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function blockable(): MorphTo
    {
        return $this->morphTo();
    }

    public function blocker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'blocked_by');
    }
}
