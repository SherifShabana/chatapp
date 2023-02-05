<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{

    protected $table = 'students';
    public $timestamps = true;
    protected $fillable = ['name', 'username', 'password', 'section_id'];

    public function channels()
    {
        return $this->morphMany(Channel::class,'chattable');
    }

    public function groups()
    {
        return $this->belongsToMany('Group');
    }

}
