@extends('app.layouts.basico')

@section('title', 'Pedido')

@section('conteudo')

    <div class="conteudo-pagina">
        <div class="titulo-pagina-2">
            <p>Visualizar - Pedido</p>
        </div>

        <div class="menu">
            <ul>
                <li><a href="{{ route('pedido.index') }}">Voltar</a></li>
                <li><a href="{{ route('pedido.edit', ['pedido' => $pedido->id]) }}">Editar</a></li>
            </ul>
        </div>

        <div class="informacao-pagina">
            <div style="width: 30%; margin-left: auto; margin-right: auto">
                <table border="1" style="text-align: left" width="100%">
                    <tr>
                        <td>ID</td>
                        <td>{{ $pedido->id }}</td>
                    </tr>
                    <tr>
                        <td>Cliente ID</td>
                        <td>{{ $pedido->cliente_id }}</td>
                    </tr>
                </table>

                <br>
                <hr>
                <h4>Itens do Pedido</h4>

                <table border="1" style="text-align: left" width="100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Produto</th>
                            <th>Quantidade</th>
                        </tr>
                    </thead>
                    @foreach ($pedido->produtos as $produto)
                        <tr>
                            <td>{{ $produto->id }}</td>
                            <td>{{ $produto->nome }}</td>
                            <td>{{ $produto->pivot->quantidade }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>

@endsection
