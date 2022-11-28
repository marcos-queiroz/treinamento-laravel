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
                <table border="1" style="text-align: left">
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
            </div>
        </div>
    </div>

@endsection
