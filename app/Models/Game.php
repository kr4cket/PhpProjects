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

    private $code;
    private $invite;
    private $error;


    public function createNewRecord()
    {
        try {

            $this->code = $this->generateCode();
            $this->invite = $this->generateCode();

            $this->create([
                'code'          => $this->code,
                'invite'        => $this->invite,
                'status'        => 1,
                'user_order'    => $this->code
            ]);
            return true;

        } catch (Exception $e) {
            $this->error = $e;
            return false;
        }

    }

    private function generateCode()
    {
        return bin2hex(random_bytes(5));
    }

    public function getData()
    {
        $idData = $this->select('id')->where('code','=', $this->code)->first();
        $gameData = [
            'id'        => $idData['id'],
            'code'      => $this->code,
            'invite'    => $this->invite
        ];

        return $gameData;
    }

    public function getErrorData()
    {
        return [
            'error' => $this->error->getCode(),
            'message' => $this->error->getMessage()
        ];
    }
}
