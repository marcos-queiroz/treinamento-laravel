<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    public function pedidos()
    {
        return $this->hasMany('App\Pedido');
    }
}
