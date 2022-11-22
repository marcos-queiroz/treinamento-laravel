<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MotivoContato;

class PrincipalController extends Controller
{
    public function principal()
    {
        $motivo_contatos = MotivoContato::all();

        return view('site.principal', compact('motivo_contatos'));
    }
}
