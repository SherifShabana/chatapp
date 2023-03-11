<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{

    protected $table = 'departments';
    public $timestamps = true;
    protected $fillable = array('name');

    public function yearLevels()
    {
        return $this->hasMany(YearLevel::class);
    }

    public function channels()
    {
        return $this->morphMany(Channel::class, 'chattable');
    }

}
