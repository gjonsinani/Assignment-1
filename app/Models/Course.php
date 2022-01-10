<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected
        $fillable = [
        'user_id',
        'course_id',
        'name',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function threads()
    {
        return $this->hasMany(Thread::class);
    }
    public function enrollments(){
        return $this->hasMany(Enrollment::class);
    }
}
