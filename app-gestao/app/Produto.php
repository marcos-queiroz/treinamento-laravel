<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    //
    protected $table = 'produtos';
    protected $fillable = ['fornecedor_id', 'nome', 'descricao', 'peso', 'unidade_id'];

    public function produtoDetalhe()
    {
        return $this->hasOne('App\ProdutoDetalhe');
    }
    
    public function fornecedor()
    {
        return $this->belongsTo('App\Fornecedor');
    }
    
    public function unidade()
    {
        return $this->belongsTo('App\Unidade');
    }

    public function pedidos()
    {
        return $this->belongsToMany('App\Pedido', 'pedido_produtos')->withPivot('id', 'quantidade', 'created_at');
    }
}
