<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{

    protected $table = 'sections';
    public $timestamps = true;
    protected $fillable = array('name', 'year_level_id');

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function yearLevel()
    {
        return $this->belongsTo(YearLevel::class);
    }

}
