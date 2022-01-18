<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Work extends Model
{
//    fillableが必要そうなので、追加
//    use HasFactory;
    protected $fillable = [
        'work',
        'worktitle',
        'caption',
        'userID',
    ];
}
