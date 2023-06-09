<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerosnalAccessToken extends Model
{
    use HasFactory;
    protected $table = 'personal_access_tokens';
    protected $fillable = ['fcm_token'];
    protected $timestamps = true;

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

}
