<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassRoom extends Model
{
    protected $fillable = ['name'];

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'class_room_user');
    }
}
