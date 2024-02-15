<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Speaker extends Model
{
    use HasFactory;
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'courses_speakers');
    }

}
