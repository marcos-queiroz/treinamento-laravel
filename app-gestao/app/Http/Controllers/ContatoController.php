<?php

namespace App\Http\Controllers;

use App\MotivoContato;
use App\SiteContato;
use Illuminate\Http\Request;


class ContatoController extends Controller
{
    public function contato()
    {
        $motivo_contatos = MotivoContato::all();

        return view('site.contato', compact('motivo_contatos'));
    }

    public function salvar(Request $request)
    {
        $regras = [
            'nome' => 'required|min:3|max:40',
            'telefone' => 'required',
            'email' => 'required|email',
            'motivo_contatos_id' => 'required',
            'mensagem' => 'required|max:2000',
        ];

        $feedback = [
            'required' => 'O campo :attribute é obrigatório',
            'nome.min' => 'O campo :attribute precisa ter no mínimo 3 caracteres',
            'nome.max' => 'O campo :attribute precisa ter no máximo 40 caracteres',
            'email.email' => 'O campo email deve ser um e-mail',
            'mensagem.min' => 'O campo :attribute precisa ter no mínimo 2000 caracteres',
        ];

        $request->validate($regras, $feedback);

        SiteContato::create($request->all());

        return redirect()->route('site.index');
    }
}
