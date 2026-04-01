<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';

    protected $primaryKey = 'id';
    protected $fillable = ['email', 'password', 'role'];

    public $timestamps = false;

    // Никогда не отдаём пароль в JSON
    protected $hidden   = ['password'];
}
