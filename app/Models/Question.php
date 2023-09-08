<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $table='questions';

    protected $fillable=['message'];

    public function aswers(){
        return $this->hasMany(Answer::class);
    }
}
