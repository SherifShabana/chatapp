<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class YearLevel extends Model
{

    protected $table = 'year_levels';
    public $timestamps = true;
    protected $fillable = array('name','department_id');

    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function channels()
    {
        return $this->morphMany(Channel::class, 'chattable');
    }

}
