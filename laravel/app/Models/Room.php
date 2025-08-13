<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $primaryKey = "roomid";
    public $incrementing = false;
    protected $keyType = "string";

    protected $fillable = [
        "roomid",
        "name",
    ];

    public function getRouteKeyName()
    {
        return "roomid";
    }
}


