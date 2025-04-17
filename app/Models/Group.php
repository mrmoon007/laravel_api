<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name', 
        'description', 
        'created_by'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'group_users')->withPivot('role', 'joined_at')->withTimestamps();
    }

    public function messages()
    {
        return $this->hasMany(GroupMessage::class);
    }
}
