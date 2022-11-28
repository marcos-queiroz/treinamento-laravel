<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    public function cliente()
    {
        return $this->belongsTo('App\Cliente');
    }

    public function produtos()
    {
        return $this->belongsToMany('App\Produto', 'pedido_produtos')->withPivot('id', 'quantidade', 'created_at');
    }
}
