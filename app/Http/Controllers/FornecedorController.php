<?php

namespace App\Http\Controllers;

use App\Fornecedor;
use Illuminate\Http\Request;

class FornecedorController extends Controller
{
    public function index()
    {
        return view('app.fornecedor.index');
    }

    public function listar(Request $request)
    {
        $fornecedores = Fornecedor::with(['produtos'])->where('nome', 'like', '%' . $request->input('nome') . '%')
            ->where('site', 'like', '%' . $request->input('site') . '%')
            ->where('uf', 'like', '%' . $request->input('uf') . '%')
            ->where('email', 'like', '%' . $request->input('email') . '%')
            ->paginate(6);

        return view('app.fornecedor.listar', ['fornecedores' => $fornecedores, 'request' => $request->all()]);
    }

    public function adicionar(Request $request)
    {
        $msg = '';

        if ($request->input('_token') != '') {

            $regras = [
                'nome' => 'required|min:3|max:40',
                'site' => 'required',
                'uf' => 'required|min:2|max:2',
                'email' => 'required|email',
            ];

            $feedback = [
                'required' => 'O campo :attribute deve ser preenchido',
                'min' => 'O campo :attribute deve ter no mínimo :min caracteres',
                'max' => 'O campo :attribute deve ter no máximo :max caracteres',
                'email' => 'O campo :attribute não foi preenchido corretamente',
            ];

            $request->validate($regras, $feedback);

            if ($request->input('id') == '') {
                Fornecedor::create($request->all());
                $msg = 'Cadastro realizado com sucesso';
            } else {
                $fornecedor = Fornecedor::find($request->input('id'));
                $update = $fornecedor->update($request->all());

                if ($update) {
                    $msg = 'Atualização realizado com sucesso';
                } else {
                    $msg = 'Atualização apresentou problema';
                }

                return redirect()->route('app.fornecedor.editar', ['id' => $request->input('id'), 'msg' => $msg]);
            }
        }

        return view('app.fornecedor.adicionar', compact('msg'));
    }

    public function editar($id, $msg = '')
    {
        $fornecedor = Fornecedor::find($id);

        return view('app.fornecedor.adicionar', compact('fornecedor', 'msg'));
    }
    
    public function excluir($id)
    {
        Fornecedor::find($id)->delete();

        return redirect()->route('app.fornecedor');
    }
}
