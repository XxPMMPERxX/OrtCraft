<?php

namespace App\Models;

use App\Casts\Split;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    use HasFactory;

    protected $keyType = 'string';

    protected $casts = [
        'types' => Split::class,
    ];

    protected static function booted()
    {
        static::creating(function (User $user) {
            empty($user->id) && $user->id = Str::uuid();
        });
    }

    public function owner()
    {
        return $this->belongsTo(User::class);
    }
}
