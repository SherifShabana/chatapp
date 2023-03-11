<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{

    protected $table = 'groups';
    public $timestamps = true;
    protected $fillable = array('name');

    public function students()
    {
        return $this->belongsToMany(Student::class);
    }

    public function channels()
    {
        return $this->morphMany(Channel::class, 'chattable');
    }

}
