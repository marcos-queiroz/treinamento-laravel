<?php

namespace App\Http\Controllers;

use App\Pedido;
use App\PedidoProduto;
use App\Produto;
use Illuminate\Http\Request;

class PedidoProdutoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Pedido $pedido
     * @return \Illuminate\Http\Response
     */
    public function create(Pedido $pedido)
    {
        $produtos = Produto::all();
        return view('app.pedido_produto.create', compact('pedido', 'produtos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Pedido $pedido
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Pedido $pedido)
    {
        $regras = [
            'produto_id' => 'exists:produtos,id',
            'quantidade' => 'required',
        ];

        $feedback = [
            'produto_id.exists' => 'O campo Produto informado não existe',
            'required' => 'O campo :attribute deve possuir um valor válido',
        ];

        $request->validate($regras, $feedback);

        $pedido->produtos()->attach(
            $request->get('produto_id'),
            [
                'quantidade' => $request->get('quantidade')
            ]
        );

        $pedido->save();

        return redirect()->route('pedido-produto.create', ['pedido' => $pedido->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PedidoProduto $pedidoProduto
     * @return \Illuminate\Http\Response
     */
    public function destroy(PedidoProduto $pedidoProduto)
    {
        // metodo convencional
        // PedidoProduto::where(['pedido_id' => $pedido->id, 'produto_id' => $produto->id])->delete();

        // metodo detach
        // $pedido->produtos()->detach($produto->id);

        $pedidoProduto->delete();

        return redirect()->route('pedido-produto.create', ['pedido' => $pedidoProduto->pedido_id]);
    }
}
