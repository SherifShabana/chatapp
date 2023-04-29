<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{

    protected $table = 'messages';
    public $timestamps = true;
    protected $fillable = array('user_id','content','channel_id','seen','type','file');
    protected $casts = [
        'seen' => 'boolean',
    ];


    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function student()
    {
        return $this->belongsToMany(Student::class);
    }

}
