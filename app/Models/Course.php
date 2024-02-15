<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Speaker;
use App\Models\Topic;

class Course extends Model
{
    use HasFactory;

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function speakers()
    {
        return $this->belongsToMany(Speaker::class, 'courses_speakers');
    }
}
