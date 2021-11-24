<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationalInstitution extends Model
{
    use HasFactory;

    // field to be filled when using mass assignment
    protected $fillable = [
        'name', 'created_by', 'address', 'back_image', 'about'
    ];

    //a relationship to define that an educational institution belong to one user
    // and returns that user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    ####################### Relationships ####################

    public function employees()
    {
        return $this->belongsToMany(User::class, 'institution_employees', 'institution_id', 'user_id');
    }
}
