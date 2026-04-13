<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'base_mark', 'image', 'teacher_id', 'is_deleted'];
    public function teacher() { return $this->belongsTo(Teacher::class); }
    public function enrollments() { return $this->hasMany(Enrollment::class); }
}