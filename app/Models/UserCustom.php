<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCustom extends Model
{
    use HasFactory;

    protected $table = 'Usuario';

    protected $fillable = [
        'name',
        'email',
        'password',
    ];
}
