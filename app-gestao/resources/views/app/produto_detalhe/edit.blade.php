@extends('app.layouts.basico')

@section('title', 'Produto')

@section('conteudo')

    <div class="conteudo-pagina">
        <div class="titulo-pagina-2">
            <p>Detalhes do Produto - Editar</p>
        </div>

        <div class="menu">
            <ul>
                <li><a href="#">Voltar</a></li>
            </ul>
        </div>

        <div class="informacao-pagina">

            <h4>Produto</h4>
            <p>Nome: {{ $produto_detalhe->produto->nome }}</p>
            <p>Descrição: {{ $produto_detalhe->produto->descricao }}</p>

            <div style="width: 30%; margin-left: auto; margin-right: auto">
                @component('app.produto_detalhe._components.form_create_edit', ['unidades' => $unidades, 'produto_detalhe' => $produto_detalhe])
                @endcomponent
            </div>
        </div>
    </div>

@endsection
