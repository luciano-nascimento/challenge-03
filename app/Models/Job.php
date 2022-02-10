<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
    ];

    public static $validatorAttributes = [
        'title' => 'required|max:100',
        'description' => 'required',
    ];

    public static function getValidatorAttributes()
    {
        return Self::$validatorAttributes;
    }
}
