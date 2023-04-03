<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Section extends Model
{
    use HasFactory;

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

    public function channels()
    {
        return $this->morphMany(Channel::class, 'chattable');
    }

}
