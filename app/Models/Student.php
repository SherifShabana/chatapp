<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Student extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $table = 'students';
    public $timestamps = true;
    protected $fillable = ['name', 'username', 'password', 'section_id'];

    protected $hidden = [
        'password'
    ];

    public function channels()
    {
        return $this->morphMany(Channel::class,'chattable');
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

}
