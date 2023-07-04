<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'invite', 'status', 'user_order'];
    public $timestamps = false;
    const BEGIN = 1;
    const IN_PROCESS = 2;
    const END = 3;

    public function newGame()
    {
        $game = $this->create([
            'code'          => $this->generateCode(),
            'invite'        => $this->generateCode(),
            'status'        => self::BEGIN,
            'user_order'    => ''
        ]);

        return $game;

    }

    private function generateCode()
    {
        return bin2hex(random_bytes(5));
    }

}
