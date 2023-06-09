<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\PersonalAccessToken;

class Student extends Authenticatable
{
    use  HasApiTokens, HasFactory;

    protected $table = 'students';
    public $timestamps = true;
    protected $fillable = ['name', 'username', 'password', 'token', 'section_id'];

    protected $hidden = [
        'password'
    ];

    public function tokens()
    {
        return $this->hasMany(PersonalAccessToken::class,'tokenable_id');
    }
    public function channels()
    {
        return $this->morphMany(Channel::class, 'chattable');
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function messages()
    {
        return $this->belongsToMany(Message::class);
    }

    public function scopeOfDepartment($query, $departmentId)
    {
        return $query->whereHas('section', function ($q) use ($departmentId) {
            $q->whereHas('yearLevel', function ($q) use ($departmentId) {
                $q->where('department_id', $departmentId);
            });
        });
    }
}
