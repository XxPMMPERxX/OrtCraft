<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * ファイル
 *
 * @property int $id
 * @property int $user_id
 * @property string $path
 */
class File extends Model
{
    protected $fillable = [
        'user_id',
        'path',
    ];
}
