<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model 
{

    protected $table = 'participants';
    public $timestamps = true;
    protected $fillable = array('channel_id', 'admin_id');

}