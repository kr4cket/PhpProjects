<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    protected $fillable = ['id', 'me_ready', 'my_turn'];
    public $timestamps = false;

    public function addPlayers($firstPlayer, $secondPlayer)
    {
        $this->create(['id' => $firstPlayer],['id' => $secondPlayer]);
    }

}
