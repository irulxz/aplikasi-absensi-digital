<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tutor extends Model
{
    use HasFactory;

    protected $table = 'tutors';

    protected $fillable = [
        'user_id',
        'nip',
        'nama',
    ];

    /**
     * Relasi Tutor dengan User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi Tutor dengan Jadwal.
     */
    public function jadwals(): HasMany
    {
        return $this->hasMany(Jadwal::class);
    }
}