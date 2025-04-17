<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupMessage extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'group_id', 
        'sender_id', 
        'message'
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
