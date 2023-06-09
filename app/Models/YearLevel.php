<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class YearLevel extends Model
{
    use HasFactory;

    protected $table = 'year_levels';
    public $timestamps = true;
    protected $fillable = array('name','department_id');

    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    public function students()
    {
        return $this->hasManyThrough(Student::class, Section::class);
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
