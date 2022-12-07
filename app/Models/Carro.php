<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carro extends Model
{
    use HasFactory;
    protected $fillable = ['modelo_id', 'placa', 'disponivel', 'km'];

    public function rules(): array
    {
        return [
            'modelo_id' => 'exists:modelos,id',
            'placa' => 'required|unique:carros,placa,' . $this->id . '|max:10',
            'disponivel' => 'required|boolean',
            'km' => 'required|integer',
        ];
    }

    public function modelo()
    {
        return $this->belongsTo('App\Models\Modelo');
    }

    public function clientes()
    {
        return $this->belongsToMany('App\Models\Cliente', 'locacoes');
    }

}
