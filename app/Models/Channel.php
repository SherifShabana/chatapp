<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{

    protected $table = 'channels';
    public $timestamps = true;
    protected $fillable = array('name');

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function lastmessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    public function unseenmessages()
    {
        return $this->hasMany(Message::class)->where('seen', false);
    }

    public function chattable()
    {
        return $this->morphTo();
    }

    public function participants()
    {
        return $this->belongsToMany(User::class,'participants');
    }

}
