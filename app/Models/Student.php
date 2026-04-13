<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guard = 'student'; // Specify the guard for this model

    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'gender', 'hobbies', 'birthdate'
    ];

    protected $hidden = [
        'password'
    ];

    protected $casts = [
        'birthdate' => 'date'
    ];

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }
}