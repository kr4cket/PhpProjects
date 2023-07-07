<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shot extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    protected $fillable = ['player_id', 'x_coord','y_coord'];
    public $timestamps = false;
}
