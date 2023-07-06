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
        $this->create(['id' => $firstPlayer]);
        $this->create(['id' => $secondPlayer]);
    }

    public function getTurn($id)
    {
        $turn = $this->select('my_turn')->where("id", "=", $id)->first();
        return $turn['my_turn'];
    }
    
    public function getReady($id)
    {
        $ready = $this->select('me_ready')->where("id", "=", $id)->first();
        return $ready['me_ready'];
    }

    public function setReady($id) 
    {
        $this->where("id", "=", $id)->update(['me_ready' => 1]);
    }

}
