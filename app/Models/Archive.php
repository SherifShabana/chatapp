<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Archive extends Model
{
    use HasFactory;

    protected $table = 'archives';
    public $timestamps = true;
    protected $fillable = ['message_id', 'content', 'user_id', 'type', 'file','deleted_at'];
}
