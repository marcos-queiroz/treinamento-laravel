@extends('app.layouts.basico')

@section('title', 'Cliente')

@section('conteudo')

    <div class="conteudo-pagina">
        <div class="titulo-pagina-2">
            <p>Visualizar - Cliente</p>
        </div>

        <div class="menu">
            <ul>
                <li><a href="{{ route('cliente.index') }}">Voltar</a></li>
                <li><a href="{{ route('cliente.edit', ['cliente' => $cliente->id]) }}">Editar</a></li>
            </ul>
        </div>

        <div class="informacao-pagina">
            <div style="width: 30%; margin-left: auto; margin-right: auto">
                <table border="1" style="text-align: left">
                    <tr>
                        <td>ID</td>
                        <td>{{ $cliente->id }}</td>
                    </tr>
                    <tr>
                        <td>Nome</td>
                        <td>{{ $cliente->nome }}</td>
                    </tr>
                </table>

                <br>
            </div>
        </div>
    </div>

@endsection
