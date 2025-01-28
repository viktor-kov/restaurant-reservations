<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Reservation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $casts = [
        'date' => 'datetime',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Reservation $reservation) {
            if (! $reservation->uuid) {
                $reservation->uuid = Str::uuid()
                    ->toString();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
