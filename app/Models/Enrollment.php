<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $table = "enrollment";
    protected $primaryKey="id";
    public $incrementing = false;
    protected $keyType = 'string';

    
}
